<?php

namespace Tests\Unit\Merchants;

use App\HttpRepositories\Warehouse\WarehouseHttpRepository;
use App\Models\Merchant;
use App\Repositories\MerchantRepository;
use App\UseCases\Cache\FlushCacheUseCase;
use App\UseCases\Merchants\FindMerchantByIdUseCase;
use App\UseCases\Merchants\ToggleMerchantGeneralGoodsUseCase;
use Tests\TestCase;

class ToggleMerchantGenerateGoodsUseCaseTest extends TestCase
{
    private FindMerchantByIdUseCase $findMerchantByIdUseCase;
    private ToggleMerchantGeneralGoodsUseCase $toggleMerchantGeneralGoodsUseCase;

    protected function setUp(): void
    {
        $this->findMerchantByIdUseCase = $this->createMock(FindMerchantByIdUseCase::class);
        $merchantRepository = $this->createMock(MerchantRepository::class);
        $warehouseHttpRepository = $this->createMock(WarehouseHttpRepository::class);
        $flushCacheUseCase = $this->createMock(FlushCacheUseCase::class);
        $this->toggleMerchantGeneralGoodsUseCase = new ToggleMerchantGeneralGoodsUseCase(
            findMerchantByIdUseCase: $this->findMerchantByIdUseCase,
            warehouseHttpRepository: $warehouseHttpRepository,
            merchantRepository: $merchantRepository,
            flushCacheUseCase: $flushCacheUseCase,
        );

        parent::setUp();
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
        $merchant->has_general_goods = false;

        $this->findMerchantByIdUseCase->method('execute')->willReturn($merchant);
        $response = $this->toggleMerchantGeneralGoodsUseCase->execute($merchant->id);

        self::assertEquals(true, $response->has_general_goods);
        self::assertIsObject($merchant, $response);
    }
}
