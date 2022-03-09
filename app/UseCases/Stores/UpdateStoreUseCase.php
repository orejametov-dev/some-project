<?php

namespace App\UseCases\Stores;

use App\DTOs\Stores\UpdateStoresDTO;
use App\Modules\Merchants\Models\Store;
use App\UseCases\Cache\FlushCacheUseCase;

class UpdateStoreUseCase
{
    public function __construct(
        private FindStoreByIdUseCase $findStoresUseCase,
        private FlushCacheUseCase $flushCacheUseCase
    ) {
    }

    public function execute(UpdateStoresDTO $updateStoresDTO): Store
    {
        $store = $this->findStoresUseCase->execute($updateStoresDTO->store_id);

        $store->name = $updateStoresDTO->name;
        $store->phone = $updateStoresDTO->phone;
        $store->address = $updateStoresDTO->address;
        $store->region = $updateStoresDTO->region;
        $store->district = $updateStoresDTO->district;
        $store->lat = $updateStoresDTO->lat;
        $store->long = $updateStoresDTO->long;
        $store->responsible_person = $updateStoresDTO->responsible_person;
        $store->responsible_person_phone = $updateStoresDTO->responsible_person_phone;

        $store->save();

        $this->flushCacheUseCase->execute($store->merchant_id);

        return $store;
    }
}
