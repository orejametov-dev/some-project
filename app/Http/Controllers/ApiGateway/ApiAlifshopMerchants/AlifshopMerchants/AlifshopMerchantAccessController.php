<?php

namespace App\Http\Controllers\ApiGateway\ApiAlifshopMerchants\AlifshopMerchants;

use App\Exceptions\BusinessException;
use App\Http\Controllers\ApiGateway\ApiBaseController;
use App\Http\Requests\ApiPrm\AlifshopMerchant\StoreAlifshopMerchantUsers;
use App\HttpServices\Core\CoreService;
use App\HttpServices\Hooks\DTO\HookData;
use App\Jobs\SendHook;
use App\Modules\AlifshopMerchants\Models\AlifshopMerchantAccess;
use App\Modules\AlifshopMerchants\Models\AlifshopMerchantStores;
use App\Modules\Companies\Models\CompanyUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AlifshopMerchantAccessController extends ApiBaseController
{
    public function index(Request $request)
    {
        $alifshop_merchant_accesses = AlifshopMerchantAccess::query()
            ->with('company_user:id,user_id,phone,full_name')
            ->filterRequest($request)
            ->orderRequest($request);

        return $alifshop_merchant_accesses->paginate($request->query('per_page') ?? 15);
    }

    public function show($id)
    {
        return AlifshopMerchantAccess::query()->findOrFail($id);
    }

    public function store(StoreAlifshopMerchantUsers $request)
    {
        $user = CoreService::getUserById($request->input('user_id')); // нужно поменять на Auth сервер
        if (!$user)
            throw new BusinessException('Пользователь не найден', 'user_not_exists', 404);

        $alifshop_merchant_store = AlifshopMerchantStores::query()->findOrFail($request->input('store_id'));

        $company_user = CompanyUser::query()->where('user_id', $user->id)->firstOrNew();
        $company_user->user_id = $user->id;
        $company_user->company_id = $alifshop_merchant_store->alifshopMerchant->company->id;
        $company_user->save();

        $alifshop_merchant_access_exists = AlifshopMerchantAccess::query()
            ->whereHas('company_users' , function ($query) use ($request) {
                $query->where(['user_id' => $request->input('user_id')])
                    ->exists();
            });

        if ($alifshop_merchant_access_exists) {
            return response()->json([
                'code' => 'user_already_exists',
                'message' => 'Пользователь является сотрудником другого мерчанта.'
            ], 400);
        }

        $alifshop_merchant = $alifshop_merchant_store->alifshopMerchant;
        if ($alifshop_merchant_access = AlifshopMerchantAccess::withTrashed()
            ->whereHas('company_users' , function ($query) use ($user) {
                $query->where('user_id', $user->id)->first(); //нужно уточнить
            })) {
                $alifshop_merchant_access->restore();
        } else {
            $alifshop_merchant_access = new AlifshopMerchantAccess();
        }

        $alifshop_merchant_access->alifshop_merchant()->associate($alifshop_merchant);
        $alifshop_merchant_access->alifshop_merchant_store()->associate($alifshop_merchant_store->id);

        $alifshop_merchant_access->save();

        SendHook::dispatch(new HookData(
            service: 'merchants',
            hookable_type: $alifshop_merchant_access->getTable(),
            hookable_id: $alifshop_merchant_access->id,
            created_from_str: 'PRM',
            created_by_id: $this->user->id,
            body: 'Сотрудник создан',
            keyword: 'Сотрудник добавлен в магазин: (store_id: ' . $alifshop_merchant_store->id . ', store_name: ' . $alifshop_merchant_store->name . ')',
            action: 'create',
            class: 'info',
            action_at: null,
            created_by_str: $this->user->name,
        ));

        Cache::tags($alifshop_merchant->id)->flush();

        return $alifshop_merchant_access;
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'store_id' => 'required|integer'
        ]);

        $alifshop_merchant_access = AlifshopMerchantAccess::query()->findOrFail($id);
        $alifshop_merchant = $alifshop_merchant_access->alifshopMerchant;
        $old_store = $alifshop_merchant_access->alifshopMerchantStores;
        $alifshop_merchant_store = $alifshop_merchant->alifshop_merchant_stores()->where(['id' => $request->input('store_id')])->firstOrFail(); //нужно уточнить

        $alifshop_merchant_access->alifshop_merchant_store()->associate($alifshop_merchant_store);

        $alifshop_merchant_access->save();

        SendHook::dispatch(new HookData(
            service: 'merchants',
            hookable_type: $alifshop_merchant_access->getTable(),
            hookable_id: $alifshop_merchant_access->id,
            created_from_str: 'PRM',
            created_by_id: $this->user->id,
            body: 'Сотрудник обновлен',
            keyword: 'Сотруднику поменяли магазин: old_store: (' . $old_store->id . ', ' . $old_store->name . ') -> ' . 'store: (' . $alifshop_merchant_store->id . ', ' . $alifshop_merchant_store->name . ')',
            action: 'update',
            class: 'warning',
            action_at: null,
            created_by_str: $this->user->name,
        ));

        Cache::tags($alifshop_merchant->id)->flush();

        return $alifshop_merchant_access;
    }

}
