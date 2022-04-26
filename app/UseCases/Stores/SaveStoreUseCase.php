<?php

namespace App\UseCases\Stores;

use App\DTOs\Stores\StoreStoresDTO;
use App\Exceptions\BusinessException;
use App\Models\Store;
use App\Repositories\StoreRepository;
use App\UseCases\Cache\FlushCacheUseCase;
use App\UseCases\Merchants\FindMerchantByIdUseCase;

class SaveStoreUseCase
{
    public function __construct(
        private FindMerchantByIdUseCase $findMerchantUseCase,
        private FlushCacheUseCase $flushCacheUseCase,
        private StoreRepository $storeRepository,
    ) {
    }

    public function execute(StoreStoresDTO $storeStoresDTO): Store
    {
        $merchant = $this->findMerchantUseCase->execute($storeStoresDTO->getMerchantId());

        if ($this->storeRepository->checkToNameExists($storeStoresDTO->getName()) === true) {
            throw new BusinessException('Указанное имя уже занято другим магазином', 'store_name_exists', 400);
        }

        $merchant_store = new Store();
        $merchant_store->name = $storeStoresDTO->getName();
        $merchant_store->merchant_id = $merchant->id;
        $merchant_store->responsible_person = $storeStoresDTO->getResponsiblePerson();
        $merchant_store->responsible_person_phone = $storeStoresDTO->getResponsiblePersonPhone();
        $merchant_store->address = $storeStoresDTO->getAddress();
        $merchant_store->region = $storeStoresDTO->getRegion();
        $merchant_store->district = $storeStoresDTO->getDistrict();

        if ($this->storeRepository->checkForTheCountByMerchantId($merchant->id) === 0) {
            $merchant_store->is_main = true;
        }

        if ($storeStoresDTO->getResponsiblePerson() === null) {
            $main_store = $this->storeRepository->getByIsMainTrueMerchantId($merchant->id);

            if ($main_store !== null) {
                $merchant_store->responsible_person = $main_store->responsible_person;
                $merchant_store->responsible_person_phone = $main_store->responsible_person_phone;
            }
        }

        $this->storeRepository->save($merchant_store);

        $this->flushCacheUseCase->execute($merchant->id);

        return $merchant_store;
    }
}
