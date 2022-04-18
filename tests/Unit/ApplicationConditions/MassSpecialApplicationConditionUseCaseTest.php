<?php

namespace Tests\Unit\ApplicationConditions;

use Alifuz\Utils\Gateway\Entities\Auth\GatewayAuthUser;
use App\HttpRepositories\Alifshop\AlifshopHttpRepository;
use App\Repositories\ApplicationConditionRepository;
use App\Repositories\MerchantRepository;
use App\Repositories\StoreRepository;
use App\UseCases\ApplicationConditions\CheckStartedAtAndFinishedAtConditionUseCase;
use App\UseCases\ApplicationConditions\MassSpecialStoreApplicationConditionUseCase;
use App\UseCases\Cache\FlushCacheUseCase;
use Tests\TestCase;

class MassSpecialApplicationConditionUseCaseTest extends TestCase
{
    private AlifshopHttpRepository $alifshopHttpRepository;
    private CheckStartedAtAndFinishedAtConditionUseCase $checkStartedAtAndFinishedAtConditionUseCase;
    private FlushCacheUseCase $flushCacheUseCase;
    private GatewayAuthUser $gatewayAuthUser;
    private ApplicationConditionRepository $applicationConditionRepository;
    private MerchantRepository $merchantRepository;
    private StoreRepository $storeRepository;
    private MassSpecialStoreApplicationConditionUseCase $massSpecialStoreApplicationConditionUseCase;

    public function setUp(): void
    {
        parent::setUp();

        $this->alifshopHttpRepository = $this->createMock(AlifshopHttpRepository::class);
        $this->checkStartedAtAndFinishedAtConditionUseCase = $this->createMock(CheckStartedAtAndFinishedAtConditionUseCase::class);
        $this->flushCacheUseCase = $this->createMock(FlushCacheUseCase::class);
        $this->gatewayAuthUser = $this->createMock(GatewayAuthUser::class);
        $this->applicationConditionRepository = $this->createMock(ApplicationConditionRepository::class);
        $this->merchantRepository = $this->createMock(MerchantRepository::class);
        $this->storeRepository = $this->createMock(StoreRepository::class);
        $this->massSpecialStoreApplicationConditionUseCase = new MassSpecialStoreApplicationConditionUseCase(
            alifshopHttpRepository: $this->alifshopHttpRepository,
            checkStartedAtAndFinishedAtConditionUseCase: $this->checkStartedAtAndFinishedAtConditionUseCase,
            flushCacheUseCase: $this->flushCacheUseCase,
            gatewayAuthUser: $this->gatewayAuthUser,
            applicationConditionRepository: $this->applicationConditionRepository,
            merchantRepository: $this->merchantRepository,
            storeRepository: $this->storeRepository,
        );
    }
}
