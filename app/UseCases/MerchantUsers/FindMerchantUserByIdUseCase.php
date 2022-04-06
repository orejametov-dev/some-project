<?php

namespace App\UseCases\MerchantUsers;

use App\Exceptions\NotFoundException;
use App\Models\AzoMerchantAccess;

class FindMerchantUserByIdUseCase
{
    public function execute(int $merchant_user_id): AzoMerchantAccess
    {
        $azo_merchant_access = AzoMerchantAccess::query()->find($merchant_user_id);
        if ($azo_merchant_access === null) {
            throw new NotFoundException('Сотрудник не найден');
        }

        return $azo_merchant_access;
    }
}
