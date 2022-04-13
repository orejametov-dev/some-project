<?php

namespace Tests\Unit;

use App\Exceptions\NotFoundException;
use App\Models\Merchant;
use App\Repositories\MerchantRepository;
use App\UseCases\Merchants\FindMerchantByIdUseCase;
use PHPUnit\Framework\TestCase;

class FindMerchantByIdUseCaseTest extends TestCase
{
    private MerchantRepository $merchantRepository;
    private FindMerchantByIdUseCase $findMerchantByIdUseCase;

    protected function setUp(): void
    {
        $this->merchantRepository = $this->createMock(MerchantRepository::class);
        $this->findMerchantByIdUseCase = new FindMerchantByIdUseCase($this->merchantRepository);
    }

    public function testNotFound(): void
    {
        $this->expectException(NotFoundException::class);

        $this->merchantRepository->method('findById')->willReturn(null);

        $this->findMerchantByIdUseCase->execute(1);
    }

    public function testSuccess(): void
    {
        $merchant = new Merchant();
        $merchant->id = 1;
        $merchant->name = 'test';
        $merchant->legal_name = 'test';
        $merchant->legal_name_prefix = 'LLC';
        $merchant->token = 'test';
        $merchant->maintainer_id = 1;
        $merchant->company_id = 1;

        $this->merchantRepository->method('findById')->willReturn($merchant);
        $response = $this->findMerchantByIdUseCase->execute(1);

        static::assertEquals($merchant->id, $response->id);
    }
}
