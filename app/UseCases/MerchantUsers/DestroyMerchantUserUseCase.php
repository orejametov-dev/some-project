<?php

declare(strict_types=1);

namespace App\UseCases\MerchantUsers;

use Alifuz\Utils\Gateway\Entities\Auth\GatewayAuthUser;
use App\HttpRepositories\Auth\AuthHttpRepository;
use App\HttpRepositories\Hooks\DTO\HookData;
use App\Jobs\SendHook;
use App\Jobs\ToggleMerchantRoleOfUser;
use App\UseCases\Stores\FindStoreByIdUseCase;

class DestroyMerchantUserUseCase
{
    public function __construct(
        private FindMerchantUserByIdUseCase $findMerchantUserUseCase,
        private FindStoreByIdUseCase $findStoreByIdUseCase,
        private GatewayAuthUser $authUser,
        private FlushMerchantUserCacheUseCase $flushMerchantUserCacheUseCase
    ) {
    }

    public function execute(int $merchant_user_id): void
    {
        $azo_merchant_access = $this->findMerchantUserUseCase->execute($merchant_user_id);
        $store = $this->findStoreByIdUseCase->execute($azo_merchant_access->store_id);

        $azo_merchant_access->delete();
        SendHook::dispatch(new HookData(
            service: 'merchants',
            hookable_type: $azo_merchant_access->getTable(),
            hookable_id: $azo_merchant_access->id,
            created_from_str: 'PRM',
            created_by_id: $this->authUser->getId(),
            body: 'Сотрудник удален',
            keyword: 'Сотрудник удален из магазина: (' . $store->id . ', ' . $azo_merchant_access->store->name . ')',
            action: 'delete',
            class: 'danger',
            action_at: null,
            created_by_str: $this->authUser->getName(),
        ));

        $this->flushMerchantUserCacheUseCase->execute($azo_merchant_access->user_id, $azo_merchant_access->merchant_id);
        ToggleMerchantRoleOfUser::dispatch($azo_merchant_access->user_id, AuthHttpRepository::DEACTIVATE_MERCHANT_ROLE);
    }
}
