<?php

namespace Tests\Unit\Stores;

use App\DTOs\Stores\UpdateStoresDTO;
use App\Models\Store;
use App\Repositories\StoreRepository;
use App\UseCases\Cache\FlushCacheUseCase;
use App\UseCases\Stores\FindStoreByIdUseCase;
use App\UseCases\Stores\UpdateStoreUseCase;
use Tests\TestCase;

class UpdateStoreUseCaseTest extends TestCase
{
    private FindStoreByIdUseCase $findStoresUseCase;
    private FlushCacheUseCase $flushCacheUseCase;
    private StoreRepository $storeRepository;
    private UpdateStoreUseCase $updateStoreUseCase;

    public function setUp(): void
    {
        parent::setUp();

        $this->findStoresUseCase = $this->createMock(FindStoreByIdUseCase::class);
        $this->flushCacheUseCase = $this->createMock(FlushCacheUseCase::class);
        $this->storeRepository = $this->createMock(StoreRepository::class);
        $this->updateStoreUseCase = new UpdateStoreUseCase(
            findStoresUseCase: $this->findStoresUseCase,
            flushCacheUseCase: $this->flushCacheUseCase,
            storeRepository: $this->storeRepository,
        );
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

        $storeDTO = UpdateStoresDTO::fromArray([
            'name' => 'test2',
            'phone' => 2,
            'address' => 'test',
            'responsible_person' => 'test',
            'responsible_person_phone' => 2,
            'region' => 'test2',
            'district' => 'test2',
            'lat' => 2,
            'long' => 2,
        ]);

        $this->findStoresUseCase->method('execute')->willReturn($store);
        $response = $this->updateStoreUseCase->execute($store->id, $storeDTO);

        static::assertEquals($storeDTO->getName(), $response->name);
        static::assertEquals($storeDTO->getPhone(), $response->phone);
        static::assertEquals($storeDTO->getAddress(), $response->address);
        static::assertEquals($storeDTO->getResponsiblePerson(), $response->responsible_person);
        static::assertEquals($storeDTO->getResponsiblePersonPhone(), $response->responsible_person_phone);
        static::assertEquals($storeDTO->getRegion(), $response->region);
        static::assertEquals($storeDTO->getDistrict(), $response->district);
        static::assertEquals($storeDTO->getLat(), $response->lat);
        static::assertEquals($storeDTO->getLong(), $response->long);
    }
}
