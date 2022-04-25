<?php

namespace Tests\Unit\Merchants;

use App\HttpRepositories\Storage\StorageHttpRepository;
use App\Models\Merchant;
use App\Repositories\MerchantRepository;
use App\UseCases\Merchants\DeleteMerchantLogoUseCase;
use App\UseCases\Merchants\FindMerchantByIdUseCase;
use Tests\TestCase;

class DeleteMerchantLogoUseCaseTest extends TestCase
{
    private FindMerchantByIdUseCase $findMerchantByIdUseCase;
    private DeleteMerchantLogoUseCase $deleteMerchantLogoUseCase;

    public function setUp(): void
    {
        $this->findMerchantByIdUseCase = $this->createMock(FindMerchantByIdUseCase::class);
        $storageHttpRepository = $this->createMock(StorageHttpRepository::class);
        $merchantRepository = $this->createMock(MerchantRepository::class);
        $this->deleteMerchantLogoUseCase = new DeleteMerchantLogoUseCase(
            findMerchantByIdUseCase: $this->findMerchantByIdUseCase,
            storageHttpRepository: $storageHttpRepository,
            merchantRepository: $merchantRepository,
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
        $merchant->logo_url = '/test/test';

        $this->findMerchantByIdUseCase->method('execute')->willReturn($merchant);
        $this->deleteMerchantLogoUseCase->execute($merchant->id);

        static::assertEquals(null, $merchant->logo_url);
    }
}
