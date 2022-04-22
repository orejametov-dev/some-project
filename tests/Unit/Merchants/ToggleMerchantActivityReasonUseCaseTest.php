<?php

namespace Tests\Unit\Merchants;

use Alifuz\Utils\Gateway\Entities\Auth\GatewayAuthUser;
use App\Exceptions\NotFoundException;
use App\HttpRepositories\Prm\CompanyHttpRepository;
use App\Models\ActivityReason;
use App\Models\Merchant;
use App\Repositories\ActivityReasonRepository;
use App\Repositories\MerchantActivityRepository;
use App\Repositories\MerchantRepository;
use App\UseCases\Cache\FlushCacheUseCase;
use App\UseCases\Merchants\FindMerchantByIdUseCase;
use App\UseCases\Merchants\ToggleMerchantActivityReasonUseCase;
use PHPUnit\Framework\TestCase;

class ToggleMerchantActivityReasonUseCaseTest extends TestCase
{
    private FindMerchantByIdUseCase $findMerchantByIdUseCase;
    private ToggleMerchantActivityReasonUseCase $toggleMerchantActivityReasonUseCase;
    private ActivityReasonRepository $activityReasonRepository;

    public function setUp(): void
    {
        $flushCacheUseCase = $this->createMock(FlushCacheUseCase::class);
        $companyHttpRepository = $this->createMock(CompanyHttpRepository::class);
        $this->findMerchantByIdUseCase = $this->createMock(FindMerchantByIdUseCase::class);
        $gatewayAuthUser = $this->createMock(GatewayAuthUser::class);
        $merchantRepository = $this->createMock(MerchantRepository::class);
        $this->activityReasonRepository = $this->createMock(ActivityReasonRepository::class);
        $merchantActivityRepository = $this->createMock(MerchantActivityRepository::class);
        $this->toggleMerchantActivityReasonUseCase = new ToggleMerchantActivityReasonUseCase(
            flushCacheUseCase: $flushCacheUseCase,
            companyHttpRepository: $companyHttpRepository,
            findMerchantByIdUseCase: $this->findMerchantByIdUseCase,
            gatewayAuthUser: $gatewayAuthUser,
            merchantRepository: $merchantRepository,
            activityReasonRepository: $this->activityReasonRepository,
            merchantActivityRepository: $merchantActivityRepository,
        );
    }

    public function testNotFoundActivityReason()
    {
        $this->expectException(NotFoundException::class);
        $this->activityReasonRepository->getByIdWithType('MERCHANT', 1);
        $this->toggleMerchantActivityReasonUseCase->execute(1, 2);
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
        $merchant->active = false;

        $activity_reason = new ActivityReason();
        $activity_reason->id = 1;
        $activity_reason->body = 'test';
        $activity_reason->type = 'MERCHANT';
        $activity_reason->active = true;

        $this->activityReasonRepository->method('getByIdWithType')->willReturn($activity_reason);
        $this->findMerchantByIdUseCase->method('execute')->willReturn($merchant);
        $response = $this->toggleMerchantActivityReasonUseCase->execute($merchant->id, $activity_reason->id);

        static::assertIsObject($merchant, $response);
        static::assertEquals(true, $response->active);
    }
}
