<?php

namespace Tests\Unit\Merchants;

use App\DTOs\Merchants\UpdateMerchantDTO;
use App\Exceptions\BusinessException;
use App\Models\Merchant;
use App\Repositories\MerchantRepository;
use App\UseCases\Cache\FlushCacheUseCase;
use App\UseCases\Merchants\FindMerchantByIdUseCase;
use App\UseCases\Merchants\UpdateMerchantUseCase;
use PHPUnit\Framework\TestCase;

class UpdateMerchantUseCaseTest extends TestCase
{
    private MerchantRepository $merchantRepository;
    private FindMerchantByIdUseCase $findMerchantUseCase;
    private UpdateMerchantUseCase $updateMerchantUseCase;

    public function setUp(): void
    {
        $this->merchantRepository = $this->createMock(MerchantRepository::class);
        $flushCacheUseCase = $this->createMock(FlushCacheUseCase::class);
        $this->findMerchantUseCase = $this->createMock(FindMerchantByIdUseCase::class);
        $this->updateMerchantUseCase = new UpdateMerchantUseCase(
            merchantRepository: $this->merchantRepository,
            flushCacheUseCase: $flushCacheUseCase,
            findMerchantUseCase: $this->findMerchantUseCase,
        );
    }

    public function testMerchantNameExists()
    {
        $updateMerchantDTO = UpdateMerchantDTO::fromArray([
            'name' => 'test2',
            'legal_name' => 'test2',
            'legal_name_prefix' => 'LLC',
            'token' => 'test-2',
        ]);

        $this->expectException(BusinessException::class);
        $this->merchantRepository->method('checkToNameExistsByIgnoringId')->willReturn(true);
        $this->updateMerchantUseCase->execute(1, $updateMerchantDTO);
    }

    public function testMerchantTokenExists()
    {
        $updateMerchantDTO = UpdateMerchantDTO::fromArray([
            'name' => 'test2',
            'legal_name' => 'test2',
            'legal_name_prefix' => 'LLC',
            'token' => 'test-2',
        ]);

        $this->expectException(BusinessException::class);
        $this->merchantRepository->method('checkToTokenExistsByIgnoringId')->willReturn(true);
        $this->updateMerchantUseCase->execute(1, $updateMerchantDTO);
    }

    public function testSuccess()
    {
        $updateMerchantDTO = UpdateMerchantDTO::fromArray([
            'name' => 'test2',
            'legal_name' => 'test2',
            'legal_name_prefix' => 'LLC',
            'token' => 'test-2',
        ]);

        $merchant = new Merchant();
        $merchant->id = 1;
        $merchant->name = 'test';
        $merchant->legal_name = 'test';
        $merchant->legal_name_prefix = 'LLC';
        $merchant->token = 'test';
        $merchant->maintainer_id = 1;
        $merchant->company_id = 1;

        $this->findMerchantUseCase->method('execute')->willReturn($merchant);
        $response = $this->updateMerchantUseCase->execute($merchant->id, $updateMerchantDTO);

        static::assertEquals($updateMerchantDTO->getName(), $response->name);
        static::assertEquals($updateMerchantDTO->getLegalName(), $response->legal_name);
        static::assertEquals($updateMerchantDTO->getLegalNamePrefix(), $response->legal_name_prefix);
        static::assertEquals($updateMerchantDTO->getToken(), $response->token);
    }
}
