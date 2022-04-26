<?php

namespace App\UseCases\Stores;

use App\DTOs\Stores\UpdateStoresDTO;
use App\Models\Store;
use App\Repositories\StoreRepository;
use App\UseCases\Cache\FlushCacheUseCase;

class UpdateStoreUseCase
{
    public function __construct(
        private FindStoreByIdUseCase $findStoresUseCase,
        private FlushCacheUseCase $flushCacheUseCase,
        private StoreRepository $storeRepository,
    ) {
    }

    public function execute(int $id, UpdateStoresDTO $updateStoresDTO): Store
    {
        $store = $this->findStoresUseCase->execute($id);

        $store->name = $updateStoresDTO->getName();
        $store->phone = $updateStoresDTO->getPhone();
        $store->address = $updateStoresDTO->getAddress();
        $store->region = $updateStoresDTO->getRegion();
        $store->district = $updateStoresDTO->getDistrict();
        $store->lat = $updateStoresDTO->getLat();
        $store->long = $updateStoresDTO->getLong();
        $store->responsible_person = $updateStoresDTO->getResponsiblePerson();
        $store->responsible_person_phone = $updateStoresDTO->getResponsiblePersonPhone();

        $this->storeRepository->save($store);

        $this->flushCacheUseCase->execute($store->merchant_id);

        return $store;
    }
}
