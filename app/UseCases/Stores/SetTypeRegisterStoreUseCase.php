<?php

namespace App\UseCases\Stores;

use App\Models\Store;
use App\Repositories\StoreRepository;
use App\Services\ClientTypeRegisterService;
use App\UseCases\Cache\FlushCacheUseCase;

class SetTypeRegisterStoreUseCase
{
    public function __construct(
        private FindStoreByIdUseCase $findStoresUseCase,
        private FlushCacheUseCase $flushCacheUseCase,
        private ClientTypeRegisterService $clientTypeRegisterService,
        private StoreRepository $storeRepository
    ) {
    }

    public function execute(int $id, string $client_type_register) : Store
    {
        $client_type_register = $this->clientTypeRegisterService->getOneByKey($client_type_register);

        $store = $this->findStoresUseCase->execute($id);
        $store->client_type_register = $client_type_register['key'];
        $this->storeRepository->save($store);

        $this->flushCacheUseCase->execute($store->merchant_id);

        return $store;
    }
}
