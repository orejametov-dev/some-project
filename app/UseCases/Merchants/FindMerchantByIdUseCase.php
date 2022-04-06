<?php

namespace App\UseCases\Merchants;

use App\Exceptions\NotFoundException;
use App\Models\Merchant;

class FindMerchantByIdUseCase
{
    public function execute(int $merchant_id): Merchant
    {
        $merchant = Merchant::query()->find($merchant_id);
        if ($merchant === null) {
            throw new NotFoundException('Мерчант не найден');
        }

        return $merchant;
    }
}
