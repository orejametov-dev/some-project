<?php

namespace App\UseCases\MerchantUsers;

use App\Exceptions\BusinessException;
use App\Models\AzoMerchantAccess;

class FindMerchantUserByIdUseCase
{
    public function execute(int $merchant_user_id): AzoMerchantAccess
    {
        $azo_merchant_access = AzoMerchantAccess::query()->find($merchant_user_id);
        if ($azo_merchant_access === null) {
            throw new BusinessException('Сотрудник не найден', 'object_not_found', 404);
        }

        return $azo_merchant_access;
    }
}
