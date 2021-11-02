<?php


namespace App\Http\Controllers\ApiGateway\AzoMerchants\Merchants;


use App\Exceptions\BusinessException;
use App\Http\Controllers\ApiGateway\ApiBaseController;
use App\Http\Requests\ApiPrm\MerchantUsers\StoreMerchantUsers;
use App\HttpServices\Auth\AuthMicroService;
use App\HttpServices\Hooks\DTO\HookData;
use App\Jobs\SendHook;
use App\Jobs\ToggleMerchantRoleOfUser;
use App\Modules\Companies\Models\CompanyUser;
use App\Modules\Merchants\Models\AzoMerchantAccess;
use App\Modules\Merchants\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AzoMerchantAccessesController extends ApiBaseController
{
    public function index(Request $request)
    {
        $azo_merchant_accesses = AzoMerchantAccess::query()
            ->with(['merchant', 'store'])
            ->filterRequest($request)
            ->orderRequest($request);


        if ($request->query('object') == true) {
            return $azo_merchant_accesses->first();
        }

        return $azo_merchant_accesses->paginate($request->query('per_page'));
    }

    public function show($id)
    {
        return AzoMerchantAccess::with(['merchant', 'store'])->findOrFail($id);
    }

    public function store(StoreMerchantUsers $request)
    {
        $user = AuthMicroService::getUserById($request->input('user_id'));

        if (!$user)
            throw new BusinessException('Пользователь не найден', 'user_not_exists', 404);

        $store = Store::query()->azo()->findOrFail($request->input('store_id'));

        $company_user = CompanyUser::query()->where('user_id', $user['data']['id'])->first();

        if($company_user){
            if($company_user->alifshop_merchant_access()->exists() and optional($company_user->company->alifshop_merchant)->id != null and optional($company_user->company->alifshop_merchant)->id != $store->merchant_id) {
                throw new BusinessException('Сотрудника нельзя прикрепить к этому мерчанту', 400);
            }
        }

        $company_user = CompanyUser::query()->where('user_id', $user['data']['id'])->firstOrNew();
        $company_user->user_id = $user['data']['id'];
        $company_user->phone = $user['data']['id'];
        $company_user->full_name = $user['data']['name'];
        $company_user->company_id = $store->merchant->company->id;
        $company_user->save();
        $azo_merchant_access_exists = AzoMerchantAccess::query()
            ->where('company_user_id', $company_user->id)
            ->exists();

        if ($azo_merchant_access_exists) {
            return response()->json([
                'code' => 'user_already_exists',
                'message' => 'Пользователь является сотрудником другого мерчанта.'
            ], 400);
        }

        $merchant = $store->merchant;
        if ($azo_merchant_access = AzoMerchantAccess::withTrashed()->where('user_id', $user['data']['id'])->first()) {
            $azo_merchant_access->restore();
        } else {
            $azo_merchant_access = new AzoMerchantAccess();
        }

        $azo_merchant_access->user_id = $request->input('user_id');
        $azo_merchant_access->user_name = $user['data']['name'];
        $azo_merchant_access->phone = $user['data']['phone'];
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


    public function destroy($id)
    {
        $azo_merchant_access = AzoMerchantAccess::query()->findOrFail($id);
        $store = $azo_merchant_access->store;

        $azo_merchant_access->delete();

        $merchant = $azo_merchant_access->merchant;

        SendHook::dispatch(new HookData(
            service: 'merchants',
            hookable_type: $azo_merchant_access->getTable(),
            hookable_id: $azo_merchant_access->id,
            created_from_str: 'PRM',
            created_by_id: $this->user->id,
            body: 'Сотрудник удален',
            keyword: 'Сотрудник удален из магазина: (' . $store->id . ', ' . $azo_merchant_access->store->name . ')',
            action: 'delete',
            class: 'danger',
            action_at: null,
            created_by_str: $this->user->name,
        ));

        Cache::tags('azo_merchants')->forget('azo_merchant_user_id_' . $azo_merchant_access->user_id);
        Cache::tags($merchant->id)->flush();

        ToggleMerchantRoleOfUser::dispatch($azo_merchant_access->user_id, AuthMicroService::DEACTIVATE_MERCHANT_ROLE);

        return response()->json(['message' => 'Сотрудник удален']);
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
            created_from_str: 'PRM',
            created_by_id: $this->user->id,
            body: 'Сотрудник обновлен',
            keyword: 'Сотруднику поменяли магазин: old_store: (' . $old_store->id . ', ' . $old_store->name . ') -> ' . 'store: (' . $store->id . ', ' . $store->name . ')',
            action: 'update',
            class: 'warning',
            action_at: null,
            created_by_str: $this->user->name,
        ));


        Cache::tags('azo_merchants')->forget('azo_merchant_user_id_' . $azo_merchant_access->user_id);
        Cache::tags($merchant->id)->flush();

        return $azo_merchant_access;
    }
}

