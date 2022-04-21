<?php

namespace Tests\Unit\Stores;

use App\Models\Store;
use App\Repositories\StoreRepository;
use App\Services\ClientTypeRegisterService;
use App\UseCases\Cache\FlushCacheUseCase;
use App\UseCases\Stores\FindStoreByIdUseCase;
use App\UseCases\Stores\SetTypeRegisterStoreUseCase;
use Tests\TestCase;

class SetTypeRegisterStoreUseCaseTest extends TestCase
{
    private FindStoreByIdUseCase $findStoresUseCase;
    private ClientTypeRegisterService $clientTypeRegisterService;
    private SetTypeRegisterStoreUseCase $setTypeRegisterStoreUseCase;

    public function setUp(): void
    {
        parent::setUp();

        $this->findStoresUseCase = $this->createMock(FindStoreByIdUseCase::class);
        $flushCacheUseCase = $this->createMock(FlushCacheUseCase::class);
        $this->clientTypeRegisterService = $this->createMock(ClientTypeRegisterService::class);
        $storeRepository = $this->createMock(StoreRepository::class);
        $this->setTypeRegisterStoreUseCase = new SetTypeRegisterStoreUseCase(
            findStoresUseCase: $this->findStoresUseCase,
            flushCacheUseCase: $flushCacheUseCase,
            clientTypeRegisterService: $this->clientTypeRegisterService,
            storeRepository: $storeRepository,
        );
    }

    public function testSuccess()
    {
        $common = [
            'key' => 'COMMON',
            'type' => 'Регистрация',
        ];

        $store = new Store();
        $store->id = 1;
        $store->name = 'test';
        $store->merchant_id = 1;
        $store->is_main = true;
        $store->address = 'test';
        $store->lat = 1;
        $store->long = 1;
        $store->phone = 1;
        $store->active = true;

        $this->clientTypeRegisterService->method('getOneByKey')->willReturn($common);
        $this->findStoresUseCase->method('execute')->willReturn($store);
        $response = $this->setTypeRegisterStoreUseCase->execute(1, ClientTypeRegisterService::COMMON);

        static::assertIsObject($store, $response);
        static::assertEquals(ClientTypeRegisterService::COMMON, $store->client_type_register);
    }
}
