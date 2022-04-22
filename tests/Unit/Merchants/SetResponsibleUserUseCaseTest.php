<?php

namespace Tests\Unit\Merchants;

use App\Exceptions\NotFoundException;
use App\HttpRepositories\Auth\AuthHttpRepository;
use App\Models\Merchant;
use App\Repositories\MerchantRepository;
use App\UseCases\Merchants\FindMerchantByIdUseCase;
use App\UseCases\Merchants\SetResponsibleUserUseCase;
use PHPUnit\Framework\TestCase;

class SetResponsibleUserUseCaseTest extends TestCase
{
    private AuthHttpRepository $authHttpRepository;
    private FindMerchantByIdUseCase $findMerchantByIdUseCase;
    private SetResponsibleUserUseCase $setResponsibleUserUseCase;

    protected function setUp(): void
    {
        $this->authHttpRepository = $this->createMock(AuthHttpRepository::class);
        $merchantRepository = $this->createMock(MerchantRepository::class);
        $this->findMerchantByIdUseCase = $this->createMock(FindMerchantByIdUseCase::class);
        $this->setResponsibleUserUseCase = new SetResponsibleUserUseCase(
            authHttpRepository: $this->authHttpRepository,
            merchantRepository: $merchantRepository,
            findMerchantUseCase: $this->findMerchantByIdUseCase
        );
    }

    public function testNotFoundUser()
    {
        $this->expectException(NotFoundException::class);
        $this->authHttpRepository->method('checkUserToExistById')->willReturn(false);
        $this->setResponsibleUserUseCase->execute(1, 100000);
    }

    public function testSuccess()
    {
        $merchant = new Merchant();
        $merchant->id = 1;
        $merchant->name = 'test';
        $merchant->legal_name = 'test';
        $merchant->legal_name_prefix = 'LLC';
        $merchant->token = 'test';
        $merchant->maintainer_id = 2;
        $merchant->company_id = 1;

        $this->findMerchantByIdUseCase->method('execute')->willReturn($merchant);
        $this->authHttpRepository->method('checkUserToExistById')->willReturn(true);

        $response = $this->setResponsibleUserUseCase->execute($merchant->id, 1);

        static::assertIsObject($merchant, $response);
    }
}
