<?php

declare(strict_types=1);

namespace App\UseCases\Merchants;

use App\Exceptions\NotFoundException;
use App\Models\Merchant;
use App\Repositories\StoreRepository;

class SetMerchantMainStoreUseCase
{
    public function __construct(
        private FindMerchantByIdUseCase $findMerchantUseCase,
        private StoreRepository $storeRepository
    ) {
    }

    public function execute(int $merchant_id, int $store_id): Merchant
    {
        $merchant = $this->findMerchantUseCase->execute($merchant_id);

        $store = $this->storeRepository->getByIdWihMerchantId($merchant->id, $store_id);

        if ($store === null) {
            throw new NotFoundException('Магазин не найден');
        }

        $store->is_main = true;
        $this->storeRepository->store($store);

        $this->storeRepository->setMainForSpecificStoreByIgnoringOtherStores($merchant->id, $store_id);

        return $merchant;
    }
}
