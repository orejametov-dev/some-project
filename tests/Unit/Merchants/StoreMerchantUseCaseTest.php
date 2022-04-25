<?php

namespace Tests\Unit\Merchants;

use Alifuz\Utils\Gateway\Entities\Auth\GatewayAuthUser;
use App\Exceptions\BusinessException;
use App\HttpRepositories\HttpResponses\Prm\CompanyHttpResponse;
use App\HttpRepositories\Prm\CompanyHttpRepository;
use App\Repositories\MerchantRepository;
use App\UseCases\Cache\FlushCacheUseCase;
use App\UseCases\Merchants\StoreMerchantUseCase;
use Tests\TestCase;

class StoreMerchantUseCaseTest extends TestCase
{
    private MerchantRepository $merchantRepository;
    private CompanyHttpRepository $companyHttpRepository;
    private StoreMerchantUseCase $storeMerchantUseCase;

    public function setUp(): void
    {
        $this->merchantRepository = $this->createMock(MerchantRepository::class);
        $this->companyHttpRepository = $this->createMock(CompanyHttpRepository::class);
        $flushCacheUseCase = $this->createMock(FlushCacheUseCase::class);
        $gatewayAuthUser = $this->createMock(GatewayAuthUser::class);
        $this->storeMerchantUseCase = new StoreMerchantUseCase(
            merchantRepository: $this->merchantRepository,
            companyHttpRepository: $this->companyHttpRepository,
            flushCacheUseCase: $flushCacheUseCase,
            gatewayAuthUser: $gatewayAuthUser
        );

        parent::setUp();
    }

    public function testModuleAzoExists()
    {
        $company = CompanyHttpResponse::fromArray([
            'id' => 1,
            'name' => 'test',
            'token' => 'test',
            'legal_name' => 'test',
            'legal_name_prefix' => 'LLC',
            'module_alifshop' => false,
        ]);

        $this->expectException(BusinessException::class);
        $this->companyHttpRepository->method('getCompanyById')->willReturn($company);
        $this->merchantRepository->method('existsByCompanyId')->willReturn(true);
        $this->storeMerchantUseCase->execute(1);
    }

    public function testSuccess()
    {
        $company = CompanyHttpResponse::fromArray([
            'id' => 1,
            'name' => 'test',
            'token' => 'test',
            'legal_name' => 'test',
            'legal_name_prefix' => 'LLC',
            'module_alifshop' => false,
        ]);

        $this->companyHttpRepository->method('getCompanyById')->willReturn($company);
        $this->merchantRepository->method('existsByCompanyId')->willReturn(false);
        $response = $this->storeMerchantUseCase->execute($company->id);

        static::assertEquals($company->id, $response->company_id);
    }
}
