<?php

declare(strict_types=1);

namespace App\UseCases\MerchantUsers;

use Alifuz\Utils\Gateway\Entities\Auth\GatewayAuthUser;
use App\HttpRepositories\Hooks\DTO\HookData;
use App\Jobs\SendHook;
use App\Modules\Merchants\Models\AzoMerchantAccess;
use App\UseCases\Merchants\FindMerchantByIdUseCase;
use App\UseCases\Stores\FindStoreByIdUseCase;

class UpdateMerchantUserUseCase
{
    public function __construct(
        private FindMerchantUserByIdUseCase $findMerchantUserUseCase,
        private FindMerchantByIdUseCase $findMerchantUseCase,
        private FlushMerchantUserCacheUseCase $flushMerchantUserCacheUseCase,
        private GatewayAuthUser $authUser,
        private FindStoreByIdUseCase $findStoreByIdUseCase,
    ) {
    }

    public function execute(int $merchant_user_id, int $store_id): AzoMerchantAccess
    {
        $azo_merchant_access = $this->findMerchantUserUseCase->execute($merchant_user_id);

        $merchant = $this->findMerchantUseCase->execute($azo_merchant_access->merchant_id);

        $old_store = $this->findStoreByIdUseCase->execute($azo_merchant_access->store_id);

        $store = $this->findStoreByIdUseCase->execute($store_id);

        $azo_merchant_access->store()->associate($store);

        $azo_merchant_access->save();

        SendHook::dispatch(new HookData(
            service: 'merchants',
            hookable_type: $azo_merchant_access->getTable(),
            hookable_id: $azo_merchant_access->id,
            created_from_str: 'PRM',
            created_by_id: $this->authUser->getId(),
            body: 'Сотрудник обновлен',
            keyword: 'Сотруднику поменяли магазин: old_store: (' . $old_store->id . ', ' . $old_store->name . ') -> ' . 'store: (' . $store->id . ', ' . $store->name . ')',
            action: 'update',
            class: 'warning',
            action_at: null,
            created_by_str: $this->authUser->getName(),
        ));

        $this->flushMerchantUserCacheUseCase->execute($azo_merchant_access->user_id, $merchant->id);

        return $azo_merchant_access;
    }
}
