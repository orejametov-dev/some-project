<?php

namespace App\UseCases\Stores;

use App\Services\ClientTypeRegisterService;
use App\UseCases\Cache\FlushCacheUseCase;

class SetTypeRegisterStoresUseCase
{
    public function __construct(
        private FindStoresUseCase $findStoresUseCase,
        private FlushCacheUseCase $flushCacheUseCase
    ) {
    }

    public function execute(int $id, string $client_type_register)
    {
        $client_type_register = ClientTypeRegisterService::getOneByKey($client_type_register);

        $store = $this->findStoresUseCase->execute($id);
        $store->client_type_register = $client_type_register['key'];
        $store->save();

        $this->flushCacheUseCase->execute($store->merchant_id);

        return $store;
    }
}
