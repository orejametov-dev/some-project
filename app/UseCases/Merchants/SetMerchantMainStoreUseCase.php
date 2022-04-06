<?php

declare(strict_types=1);

namespace App\UseCases\Merchants;

use App\Exceptions\NotFoundException;
use App\Models\Merchant;
use App\Models\Store;

class SetMerchantMainStoreUseCase
{
    public function __construct(
        private FindMerchantByIdUseCase $findMerchantUseCase
    ) {
    }

    public function execute(int $merchant_id, int $store_id): Merchant
    {
        $merchant = $this->findMerchantUseCase->execute($merchant_id);

        $store = Store::query()->where('merchant_id', $merchant->id)->find($store_id);

        if ($store === null) {
            throw new NotFoundException('Магазин не найден');
        }

        $store->is_main = true;
        $store->save();

        Store::query()->where('merchant_id', $merchant->id)->where('id', '<>', $store_id)->update([
            'is_main' => false,
        ]);

        return $merchant;
    }
}
