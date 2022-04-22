<?php

declare(strict_types=1);

namespace App\UseCases\Merchants;

use App\HttpRepositories\Warehouse\WarehouseHttpRepository;
use App\Models\Merchant;
use App\Repositories\MerchantRepository;
use App\UseCases\Cache\FlushCacheUseCase;

class ToggleMerchantGeneralGoodsUseCase
{
    public function __construct(
        private FindMerchantByIdUseCase $findMerchantByIdUseCase,
        private WarehouseHttpRepository $warehouseHttpRepository,
        private MerchantRepository $merchantRepository,
        private FlushCacheUseCase $flushCacheUseCase,
    ) {
    }

    public function execute(int $id): Merchant
    {
        $merchant = $this->findMerchantByIdUseCase->execute($id);
        $this->warehouseHttpRepository->checkDuplicateSKUs($merchant->id);

        $merchant->has_general_goods = !$merchant->has_general_goods;
        $this->merchantRepository->save($merchant);

        $this->flushCacheUseCase->execute($merchant->id);

        return $merchant;
    }
}
