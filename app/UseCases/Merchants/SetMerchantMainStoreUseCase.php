<?php

declare(strict_types=1);

namespace App\UseCases\Merchants;

use App\Exceptions\BusinessException;
use App\Modules\Merchants\Models\Merchant;
use App\Modules\Merchants\Models\Store;

class SetMerchantMainStoreUseCase
{
    public function __construct(
        private FindMerchantByIdUseCase $findMerchantUseCase
    ) {
    }

    public function execute(int $merchant_id, int $store_id): Merchant
    {
        $merchant = $this->findMerchantUseCase->execute($merchant_id);

        $store = Store::where('merchant_id', $merchant->id)->find($store_id);

        if ($store === null) {
            throw new BusinessException('Магазин не найден', 'object_not_found', 404);
        }

        $store->is_main = true;
        $store->save();

        Store::where('merchant_id', $merchant->id)->where('id', '<>', $store_id)->update([
            'is_main' => false,
        ]);

        return $merchant;
    }
}
