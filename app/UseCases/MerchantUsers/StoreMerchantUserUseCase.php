<?php

declare(strict_types=1);

namespace App\UseCases\MerchantUsers;

use Alifuz\Utils\Gateway\Entities\Auth\GatewayAuthUser;
use App\Exceptions\BusinessException;
use App\HttpRepositories\Auth\AuthHttpRepository;
use App\HttpRepositories\Hooks\DTO\HookData;
use App\HttpRepositories\Prm\CompanyUserHttpRepository;
use App\Jobs\SendHook;
use App\Jobs\ToggleMerchantRoleOfUser;
use App\Modules\Merchants\Models\AzoMerchantAccess;
use App\Modules\Merchants\Models\Store;

class StoreMerchantUserUseCase
{
    public function __construct(
        private AuthHttpRepository $authHttpRepository,
        private CompanyUserHttpRepository $companyUserHttpRepository,
        private GatewayAuthUser $authUser,
        private FlushMerchantUserCacheUseCase $flushMerchantUserCacheUseCase
    ) {
    }

    public function execute(int $store_id, int $user_id): AzoMerchantAccess
    {
        $user = $this->authHttpRepository->getUserById($user_id);
        if ($user === null) {
            throw new BusinessException('Пользователь не найден', 'object_not_found', 404);
        }

        $store = Store::query()->find($store_id);
        if ($store === null) {
            throw new BusinessException('Магазин не найден', 'object_not_found', 404);
        }

        $company_user = $this->companyUserHttpRepository->getCompanyUserByUserId($user->id);
        if ($company_user === null) {
            $company_user = $this->companyUserHttpRepository->createCompanyUser(
                user_id: $user->id,
                company_id: $store->merchant->company_id,
                phone: $user->phone,
                full_name: $user->name
            );
        }

        if (AzoMerchantAccess::query()->where('company_user_id', $company_user->id)->exists()) {
            throw new BusinessException('Пользователь является сотрудником другого мерчанта.', 'user_already_exists');
        }

        $merchant = $store->merchant;
        if ($azo_merchant_access = AzoMerchantAccess::withTrashed()->where('company_user_id', $company_user->id)->first()) {
            $azo_merchant_access->restore();
        } else {
            $azo_merchant_access = new AzoMerchantAccess();
        }

        $azo_merchant_access->user_id = $user->id;
        $azo_merchant_access->user_name = $user->name;
        $azo_merchant_access->phone = $user->phone;
        $azo_merchant_access->company_user_id = $company_user->id;
        $azo_merchant_access->merchant()->associate($merchant);
        $azo_merchant_access->store()->associate($store);

        $azo_merchant_access->save();

        SendHook::dispatch(new HookData(
            service: 'merchants',
            hookable_type: $azo_merchant_access->getTable(),
            hookable_id: $azo_merchant_access->id,
            created_from_str: 'PRM',
            created_by_id: $this->authUser->getId(),
            body: 'Сотрудник создан',
            keyword: 'Сотрудник добавлен в магазин: (store_id: ' . $store->id . ', store_name: ' . $store->name . ')',
            action: 'create',
            class: 'info',
            action_at: null,
            created_by_str: $this->authUser->getName(),
        ));

        ToggleMerchantRoleOfUser::dispatch($azo_merchant_access->user_id, AuthHttpRepository::ACTIVATE_MERCHANT_ROLE);

        $this->flushMerchantUserCacheUseCase->execute($azo_merchant_access->user_id, $merchant->id);

        return $azo_merchant_access;
    }
}
