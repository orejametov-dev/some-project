<?php

namespace Tests\Unit\Merchants;

use App\Models\Merchant;
use App\Repositories\MerchantRepository;
use App\UseCases\Cache\FlushCacheUseCase;
use App\UseCases\Merchants\FindMerchantByIdUseCase;
use App\UseCases\Merchants\ToggleHoldingInitialPaymentUseCase;
use Tests\TestCase;

class ToggleHoldingInitialPaymentUseCaseTest extends TestCase
{
    private FindMerchantByIdUseCase $findMerchantByIdUseCase;
    private ToggleHoldingInitialPaymentUseCase $toggleHoldingInitialPaymentUseCase;

    protected function setUp(): void
    {
        $this->findMerchantByIdUseCase = $this->createMock(FindMerchantByIdUseCase::class);
        $flushCacheUseCase = $this->createMock(FlushCacheUseCase::class);
        $merchantRepository = $this->createMock(MerchantRepository::class);
        $this->toggleHoldingInitialPaymentUseCase = new ToggleHoldingInitialPaymentUseCase(
            findMerchantByIdUseCase: $this->findMerchantByIdUseCase,
            flushCacheUseCase: $flushCacheUseCase,
            merchantRepository:  $merchantRepository
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
        $merchant->holding_initial_payment = false;

        $this->findMerchantByIdUseCase->method('execute')->willReturn($merchant);
        $response = $this->toggleHoldingInitialPaymentUseCase->execute($merchant->id);

        self::assertEquals(true, $response->holding_initial_payment);
        self::assertIsObject($merchant, $response);
    }
}
