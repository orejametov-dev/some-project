<?php

namespace Tests\Unit\Stores;

use App\DTOs\Stores\StoreStoresDTO;
use App\Exceptions\BusinessException;
use App\Models\Merchant;
use App\Models\Store;
use App\Repositories\StoreRepository;
use App\UseCases\Cache\FlushCacheUseCase;
use App\UseCases\Merchants\FindMerchantByIdUseCase;
use App\UseCases\Stores\SaveStoreUseCase;
use Tests\TestCase;

class SaveStoreUseCaseTest extends TestCase
{
    private FindMerchantByIdUseCase $findMerchantUseCase;
    private StoreRepository $storeRepository;
    private SaveStoreUseCase $saveStoreUseCase;

    public function setUp(): void
    {
        parent::setUp();
        $this->findMerchantUseCase = $this->createMock(FindMerchantByIdUseCase::class);
        $flushCacheUseCase = $this->createMock(FlushCacheUseCase::class);
        $this->storeRepository = $this->createMock(StoreRepository::class);
        $this->saveStoreUseCase = new SaveStoreUseCase(
            findMerchantUseCase: $this->findMerchantUseCase,
            flushCacheUseCase: $flushCacheUseCase,
            storeRepository: $this->storeRepository,
        );
    }

    public function testStoreNameExists()
    {
        $merchant = new Merchant();
        $merchant->id = 1;
        $merchant->name = 'test';
        $merchant->legal_name = 'test';
        $merchant->legal_name_prefix = 'LLC';
        $merchant->token = 'test';
        $merchant->maintainer_id = 1;
        $merchant->company_id = 1;

        $storeDTO = StoreStoresDTO::fromArray([
            'name' => 'test',
            'merchant_id' => 1,
            'address' => 'test',
            'responsible_person' => null,
            'responsible_person_phone' => null,
            'region' => 'test',
            'district' => 'test',
        ]);

        $this->expectException(BusinessException::class);
        $this->findMerchantUseCase->method('execute')->willReturn($merchant);
        $this->storeRepository->method('checkToNameExists')->willReturn(true);
        $this->saveStoreUseCase->execute($storeDTO);
    }

    public function testSuccess()
    {
        $merchant = new Merchant();
        $merchant->id = 1;
        $merchant->name = 'test';
        $merchant->legal_name = 'test';
        $merchant->legal_name_prefix = 'LLC';
        $merchant->token = 'test';
        $merchant->maintainer_id = 1;
        $merchant->company_id = 1;

        $storeDTO = StoreStoresDTO::fromArray([
            'name' => 'test',
            'merchant_id' => 1,
            'address' => 'test',
            'responsible_person' => null,
            'responsible_person_phone' => null,
            'region' => 'test',
            'district' => 'test',
        ]);

        $this->findMerchantUseCase->method('execute')->willReturn($merchant);
        $response = $this->saveStoreUseCase->execute($storeDTO);

        static::assertEquals($storeDTO->getName(), $response->name);
        static::assertEquals($storeDTO->getMerchantId(), $response->merchant_id);
        static::assertEquals($storeDTO->getAddress(), $response->address);
        static::assertEquals($storeDTO->getRegion(), $response->region);
        static::assertEquals($storeDTO->getDistrict(), $response->district);
        static::assertEquals(true, $response->is_main);
    }

    public function testSuccessIsMainExists()
    {
        $merchant = new Merchant();
        $merchant->id = 1;
        $merchant->name = 'test';
        $merchant->legal_name = 'test';
        $merchant->legal_name_prefix = 'LLC';
        $merchant->token = 'test';
        $merchant->maintainer_id = 1;
        $merchant->company_id = 1;

        $main_store = new Store();
        $main_store->id = 1;
        $main_store->name = 'test';
        $main_store->merchant_id = 1;
        $main_store->is_main = true;
        $main_store->address = 'test';
        $main_store->lat = 1;
        $main_store->long = 1;
        $main_store->phone = 1;
        $main_store->active = true;
        $main_store->responsible_person = 'test';
        $main_store->responsible_person_phone = 1;

        $storeDTO = StoreStoresDTO::fromArray([
            'name' => 'test',
            'merchant_id' => 1,
            'address' => 'test',
            'responsible_person' => null,
            'responsible_person_phone' => null,
            'region' => 'test',
            'district' => 'test',
        ]);

        $this->findMerchantUseCase->method('execute')->willReturn($merchant);
        $this->storeRepository->method('checkForTheCountByMerchantId')->willReturn(1);
        $this->storeRepository->method('getByIsMainTrueMerchantId')->willReturn($main_store);
        $response = $this->saveStoreUseCase->execute($storeDTO);

        static::assertEquals($storeDTO->getName(), $response->name);
        static::assertEquals($storeDTO->getMerchantId(), $response->merchant_id);
        static::assertEquals($storeDTO->getAddress(), $response->address);
        static::assertEquals($storeDTO->getRegion(), $response->region);
        static::assertEquals($storeDTO->getDistrict(), $response->district);
        static::assertEquals(false, $response->is_main);
        static::assertEquals($main_store->responsible_person, $response->responsible_person);
        static::assertEquals($main_store->responsible_person_phone, $response->responsible_person_phone);
    }
}
