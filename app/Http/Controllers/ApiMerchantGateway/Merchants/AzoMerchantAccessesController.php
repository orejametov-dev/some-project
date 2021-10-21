<?php


namespace App\Http\Controllers\ApiMerchantGateway\Merchants;


use App\Exceptions\BusinessException;
use App\Http\Controllers\ApiMerchantGateway\ApiBaseController;
use App\HttpServices\Auth\AuthMicroService;
use App\HttpServices\Hooks\DTO\HookData;
use App\HttpServices\Notify\NotifyMicroService;
use App\Jobs\SendHook;
use App\Jobs\ToggleMerchantRoleOfUser;
use App\Modules\Companies\Models\CompanyUser;
use App\Modules\Merchants\Models\AzoMerchantAccess;
use App\Modules\Merchants\Models\Store;
use App\Services\Helpers\Randomizr;
use App\Services\SMS\OtpProtector;
use App\Services\SMS\SmsMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

class AzoMerchantAccessesController extends ApiBaseController
{
    public function index(Request $request)
    {
        $merchantUsersQuery = AzoMerchantAccess::query()
            ->with(['merchant', 'store'])
            ->byMerchant($this->merchant_id)
            ->filterRequest($request)
            ->orderRequest($request);

        return $merchantUsersQuery->paginate($request->query('per_page'));
    }

    public function show($id)
    {
        $merchantUser = AzoMerchantAccess::query()
            ->byMerchant($this->merchant_id)
            ->findOrFail($id);

        return $merchantUser;
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'store_id' => 'required|integer'
        ]);

        $azo_merchant_access = AzoMerchantAccess::query()->findOrFail($id);
        $merchant = $azo_merchant_access->merchant;
        $old_store = $azo_merchant_access->store;
        $store = $merchant->stores()->where(['id' => $request->input('store_id')])->firstOrFail();

        $azo_merchant_access->store()->associate($store);

        $azo_merchant_access->save();

        SendHook::dispatch(new HookData(
            service: 'merchants',
            hookable_type: $azo_merchant_access->getTable(),
            hookable_id: $azo_merchant_access->id,
            created_from_str: 'MERCHANT',
            created_by_id: $this->user->id,
            body: 'Сотрудник обновлен',
            keyword: 'old_store: (' . $old_store->id . ', ' . $old_store->name . ') -> ' . 'store: (' . $store->id . ', ' . $store->name . ')',
            action: 'update',
            class: 'warning',
            action_at: null,
            created_by_str: $this->user->name,
        ));


        Cache::tags('azo_merchants')->forget('azo_merchant_user_id_' . $azo_merchant_access->user_id);
        Cache::tags($merchant->id)->flush();

        return $azo_merchant_access;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required|integer',
            'store_id' => 'required|integer'
        ]);

        $user = AuthMicroService::getUserById($request->input('user_id'));

        $user_roles = $user['data']['roles'];
        $user_roles_column = array_column($user_roles, 'name');
        $search_roles = array_search('Merchant' , $user_roles_column);

        if ($search_roles) {
            throw new BusinessException('Пользователь уже существует', 'user_exists', 400);
        }

        $phone_exists = AzoMerchantAccess::query()
            ->where('id', $user['data']['id'])
            ->exists();

        if ($phone_exists) {
            throw new BusinessException('Пользователь уже существует', 'user_exists', 400);
        }

        $store = Store::query()
            //->alifshop()
            ->findOrFail($request->input('store_id'));

        $company_user = CompanyUser::query()->where('user_id', $user['data']['id'])->firstOrNew();
        $company_user->user_id = $user['data']['id'];
        $company_user->phone = $user['data']['phone'];
        $company_user->full_name = $user['data']['name'];
        $company_user->company_id = $store->merchant->company->id;
        $company_user->save();

        $merchant = $store->merchant;

        if ($azo_merchant_access = AzoMerchantAccess::withTrashed()->where('user_id', $user['data']['id'])->first()) {
            $azo_merchant_access->restore();
        } else {
            $azo_merchant_access = new AzoMerchantAccess();
        }

        $azo_merchant_access->user_id = $user['data']['id'];
        $azo_merchant_access->user_name = $user['data']['id'];
        $azo_merchant_access->phone = $user['data']['id'];
        $azo_merchant_access->merchant()->associate($merchant);
        $azo_merchant_access->store()->associate($store->id);
        $azo_merchant_access->company_user()->associate($company_user->id);

        $azo_merchant_access->save();

        SendHook::dispatch(new HookData(
            service: 'merchants',
            hookable_type: $azo_merchant_access->getTable(),
            hookable_id: $azo_merchant_access->id,
            created_from_str: 'PRM',
            created_by_id: $this->user->id,
            body: 'Сотрудник создан',
            keyword: 'Сотрудник добавлен в магазин: (store_id: ' . $store->id . ', store_name: ' . $store->name . ')',
            action: 'create',
            class: 'info',
            action_at: null,
            created_by_str: $this->user->name,
        ));

        ToggleMerchantRoleOfUser::dispatch($azo_merchant_access->user_id, AuthMicroService::ACTIVATE_MERCHANT_ROLE);

        Cache::tags('azo_merchants')->forget('azo_merchant_user_id_' . $azo_merchant_access->user_id);
        Cache::tags($merchant->id)->flush();

        return $azo_merchant_access;


    }
}
