<?php

namespace Tests\Unit\Stores;

use App\Exceptions\NotFoundException;
use App\Models\Store;
use App\Repositories\StoreRepository;
use App\UseCases\Stores\FindStoreByIdUseCase;
use Tests\TestCase;

class FindStoreByIdUseCaseTest extends TestCase
{
    private StoreRepository $storeRepository;
    private FindStoreByIdUseCase $findStoreByIdUseCase;

    public function setUp(): void
    {
        parent::setUp();

        $this->storeRepository = $this->createMock(StoreRepository::class);
        $this->findStoreByIdUseCase = new FindStoreByIdUseCase($this->storeRepository);
    }

    public function testNotFound()
    {
        $this->expectException(NotFoundException::class);
        $this->storeRepository->method('getById')->willReturn(null);
        $this->findStoreByIdUseCase->execute(1);
    }

    public function testSuccess()
    {
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

        $this->storeRepository->method('getById')->willReturn($store);
        $response = $this->findStoreByIdUseCase->execute(1);

        static::assertEquals(1, $response->id);
        static::assertIsObject($store, $response);
    }
}
