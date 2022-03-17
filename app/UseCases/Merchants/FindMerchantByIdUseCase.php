<?php

namespace App\UseCases\Merchants;

use App\Exceptions\BusinessException;
use App\Models\Merchant;

class FindMerchantByIdUseCase
{
    public function execute(int $merchant_id): Merchant
    {
        $merchant = Merchant::query()->find($merchant_id);
        if ($merchant === null) {
            throw new BusinessException('Мерчант не найден', 'object_not_found', 404);
        }

        return $merchant;
    }
}
