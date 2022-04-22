<?php

namespace Tests\Unit\Merchants;

use App\Exceptions\NotFoundException;
use App\Models\Merchant;
use App\Models\Store;
use App\Repositories\StoreRepository;
use App\UseCases\Merchants\FindMerchantByIdUseCase;
use App\UseCases\Merchants\SetMerchantMainStoreUseCase;
use Tests\TestCase;

class SetMerchantMainStoreUseCaseTest extends TestCase
{
    private StoreRepository $storeRepository;
    private FindMerchantByIdUseCase $findMerchantByIdUseCase;
    private SetMerchantMainStoreUseCase $setMerchantMainStoreUseCase;

    protected function setUp(): void
    {
        $this->storeRepository = $this->createMock(StoreRepository::class);
        $this->findMerchantByIdUseCase = $this->createMock(FindMerchantByIdUseCase::class);
        $this->setMerchantMainStoreUseCase = new SetMerchantMainStoreUseCase(
            findMerchantUseCase: $this->findMerchantByIdUseCase,
            storeRepository: $this->storeRepository
        );
    }

    public function testNotFoundStore()
    {
        $merchant = new Merchant();
        $merchant->id = 1;
        $merchant->name = 'test';
        $merchant->legal_name = 'test';
        $merchant->legal_name_prefix = 'LLC';
        $merchant->token = 'test';
        $merchant->maintainer_id = 1;
        $merchant->company_id = 1;

        $this->expectException(NotFoundException::class);

        $this->findMerchantByIdUseCase->method('execute')->willReturn($merchant);
        $this->storeRepository->method('getByIdWihMerchantId')->willReturn(null);

        $this->setMerchantMainStoreUseCase->execute($merchant->id, 100000);
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

        $store = new Store();
        $store->id = 1;
        $store->name = 'test';
        $store->merchant_id = 1;
        $store->responsible_person = 'tester';
        $store->responsible_person_phone = '999999999';
        $store->address = 'address';
        $store->region = 'tashkent';
        $store->district = 'tashkent';
        $store->is_main = false;

        $this->findMerchantByIdUseCase->method('execute')->willReturn($merchant);
        $this->storeRepository->method('getByIdWihMerchantId')->willReturn($store);

        $response = $this->setMerchantMainStoreUseCase->execute($merchant->id, 1);

        static::assertEquals(true, $store->is_main);
        static::assertIsObject($merchant, $response);
    }
}
