<?php

namespace Tests\Unit\ApplicationConditions;

use Alifuz\Utils\Gateway\Entities\Auth\GatewayAuthUser;
use App\Exceptions\BusinessException;
use App\HttpRepositories\Core\CoreHttpRepository;
use App\Jobs\SendHook;
use App\Models\Condition;
use App\Models\Merchant;
use App\Repositories\ApplicationConditionRepository;
use App\UseCases\ApplicationConditions\DeleteApplicationConditionUseCase;
use App\UseCases\ApplicationConditions\FindConditionByIdUseCase;
use App\UseCases\Cache\FlushCacheUseCase;
use App\UseCases\Merchants\FindMerchantByIdUseCase;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class DeleteApplicationConditionUseCaseTest extends TestCase
{
    private CoreHttpRepository $coreHttpRepository;
    private FindConditionByIdUseCase $findConditionUseCase;
    private DeleteApplicationConditionUseCase $deleteApplicationConditionUseCase;
    private FindMerchantByIdUseCase $findMerchantByIdUseCase;

    public function setUp(): void
    {
        $this->coreHttpRepository = $this->createMock(CoreHttpRepository::class);
        $this->findConditionUseCase = $this->createMock(FindConditionByIdUseCase::class);
        $flushCacheUseCase = $this->createMock(FlushCacheUseCase::class);
        $gatewayAuthUser = $this->createMock(GatewayAuthUser::class);
        $this->findMerchantByIdUseCase = $this->createMock(FindMerchantByIdUseCase::class);
        $applicationConditionRepository = $this->createMock(ApplicationConditionRepository::class);
        $this->deleteApplicationConditionUseCase = new DeleteApplicationConditionUseCase(
            coreHttpRepository: $this->coreHttpRepository,
            findConditionUseCase: $this->findConditionUseCase,
            flushCacheUseCase: $flushCacheUseCase,
            gatewayAuthUser: $gatewayAuthUser,
            applicationConditionRepository: $applicationConditionRepository,
            findMerchantByIdUseCase: $this->findMerchantByIdUseCase
        );

        parent::setUp();
    }

    public function testConditionCannotBeRemoved()
    {
        $condition = new Condition();
        $condition->id = 1;
        $condition->merchant_id = 1;
        $condition->store_id = 1;
        $condition->commission = 1;
        $condition->duration = 1;
        $condition->discount = 1;
        $condition->is_special = false;
        $condition->special_offer = 'test';
        $condition->event_id = 1;
        $condition->post_merchant = 1;
        $condition->post_alifshop = 1;
        $condition->active = false;

        $this->expectException(BusinessException::class);
        $this->findConditionUseCase->method('execute')->willReturn($condition);
        $this->coreHttpRepository->method('checkApplicationToExistByConditionId')->willReturn(true);
        $this->deleteApplicationConditionUseCase->execute($condition->id);
    }

    public function testSuccess()
    {
        $condition = new Condition();
        $condition->id = 1;
        $condition->merchant_id = 1;
        $condition->store_id = 1;
        $condition->commission = 1;
        $condition->duration = 1;
        $condition->discount = 1;
        $condition->is_special = false;
        $condition->special_offer = 'test';
        $condition->event_id = 1;
        $condition->post_merchant = 1;
        $condition->post_alifshop = 1;
        $condition->active = false;

        $merchant = new Merchant();
        $merchant->id = 1;
        $merchant->name = 'test';
        $merchant->legal_name = 'test';
        $merchant->legal_name_prefix = 'LLC';
        $merchant->token = 'test';
        $merchant->maintainer_id = 1;
        $merchant->company_id = 1;

        $this->findConditionUseCase->method('execute')->willReturn($condition);
        $this->coreHttpRepository->method('checkApplicationToExistByConditionId')->willReturn(false);
        $this->findMerchantByIdUseCase->method('execute')->willReturn($merchant);
        $this->deleteApplicationConditionUseCase->execute($condition->id);

        static::assertTrue(true);
        Bus::assertDispatched(SendHook::class);
    }
}
