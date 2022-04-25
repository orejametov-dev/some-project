<?php

namespace Tests\Unit\Merchants;

use App\Models\Merchant;
use App\Repositories\MerchantRepository;
use App\UseCases\Cache\FlushCacheUseCase;
use App\UseCases\Merchants\FindMerchantByIdUseCase;
use App\UseCases\Merchants\ToggleMerchantRecommendUseCase;
use Tests\TestCase;

class ToggleMerchantRecommendUseCaseTest extends TestCase
{
    private FindMerchantByIdUseCase $findMerchantByIdUseCase;
    private ToggleMerchantRecommendUseCase $toggleMerchantRecommendUseCase;

    protected function setUp(): void
    {
        $this->findMerchantByIdUseCase = $this->createMock(FindMerchantByIdUseCase::class);
        $flushCacheUseCase = $this->createMock(FlushCacheUseCase::class);
        $merchantRepository = $this->createMock(MerchantRepository::class);
        $this->toggleMerchantRecommendUseCase = new ToggleMerchantRecommendUseCase(
            findMerchantByIdUseCase: $this->findMerchantByIdUseCase,
            flushCacheUseCase: $flushCacheUseCase,
            merchantRepository: $merchantRepository
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
        $merchant->recommend = false;

        $this->findMerchantByIdUseCase->method('execute')->willReturn($merchant);
        $response = $this->toggleMerchantRecommendUseCase->execute($merchant->id);

        self::assertEquals(true, $response->recommend);
        self::assertIsObject($merchant, $response);
    }
}
