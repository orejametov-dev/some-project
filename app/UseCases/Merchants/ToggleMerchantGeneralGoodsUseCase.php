<?php

declare(strict_types=1);

namespace App\UseCases\Merchants;

use App\HttpRepositories\Warehouse\WarehouseHttpRepository;
use App\Models\Merchant;
use Illuminate\Support\Facades\Cache;

class ToggleMerchantGeneralGoodsUseCase
{
    public function __construct(
        private FindMerchantByIdUseCase $findMerchantByIdUseCase,
        private WarehouseHttpRepository $warehouseHttpRepository
    ) {
    }

    public function execute(int $id): Merchant
    {
        $merchant = $this->findMerchantByIdUseCase->execute($id);
        $this->warehouseHttpRepository->checkDuplicateSKUs($merchant->id);

        $merchant->has_general_goods = !$merchant->has_general_goods;
        $merchant->save();

        Cache::tags($merchant->id)->flush();
        Cache::tags('azo_merchants')->flush();
        Cache::tags('company')->flush();

        return $merchant;
    }
}
