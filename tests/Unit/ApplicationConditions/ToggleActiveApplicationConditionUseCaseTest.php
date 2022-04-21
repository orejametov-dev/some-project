<?php

namespace Tests\Unit\ApplicationConditions;

use Alifuz\Utils\Gateway\Entities\Auth\GatewayAuthUser;
use App\HttpRepositories\Alifshop\AlifshopHttpRepository;
use App\Jobs\SendHook;
use App\Models\Condition;
use App\Models\Merchant;
use App\Repositories\ApplicationConditionRepository;
use App\UseCases\ApplicationConditions\FindConditionByIdUseCase;
use App\UseCases\ApplicationConditions\ToggleActiveApplicationConditionUseCase;
use App\UseCases\Cache\FlushCacheUseCase;
use App\UseCases\Merchants\FindMerchantByIdUseCase;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class ToggleActiveApplicationConditionUseCaseTest extends TestCase
{
    private FindConditionByIdUseCase $findConditionByIdUseCase;
    private ApplicationConditionRepository $applicationConditionRepository;
    private FindMerchantByIdUseCase $findMerchantByIdUseCase;
    private ToggleActiveApplicationConditionUseCase $toggleActiveApplicationConditionUseCase;

    public function setUp(): void
    {
        parent::setUp();
        $alifshopHttpRepository = $this->createMock(AlifshopHttpRepository::class);
        $this->findConditionByIdUseCase = $this->createMock(FindConditionByIdUseCase::class);
        $flushCacheUseCase = $this->createMock(FlushCacheUseCase::class);
        $gatewayAuthUser = $this->createMock(GatewayAuthUser::class);
        $this->applicationConditionRepository = $this->createMock(ApplicationConditionRepository::class);
        $this->findMerchantByIdUseCase = $this->createMock(FindMerchantByIdUseCase::class);
        $this->toggleActiveApplicationConditionUseCase = new ToggleActiveApplicationConditionUseCase(
            alifshopHttpRepository: $alifshopHttpRepository,
            findConditionUseCase: $this->findConditionByIdUseCase,
            flushCacheUseCase: $flushCacheUseCase,
            gatewayAuthUser: $gatewayAuthUser,
            applicationConditionRepository: $this->applicationConditionRepository,
            findMerchantByIdUseCase: $this->findMerchantByIdUseCase,
        );
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
        $condition->post_merchant = true;
        $condition->post_alifshop = true;
        $condition->active = false;

        $merchant = new Merchant();
        $merchant->id = 1;
        $merchant->name = 'test';
        $merchant->legal_name = 'test';
        $merchant->legal_name_prefix = 'LLC';
        $merchant->token = 'test';
        $merchant->maintainer_id = 1;
        $merchant->company_id = 1;

        $this->findConditionByIdUseCase->method('execute')->willReturn($condition);
        $this->findMerchantByIdUseCase->method('execute')->willReturn($merchant);
        $this->applicationConditionRepository->method('getByActiveTruePostAlifshopTrueWithMerchantId')->willReturn(Collection::make([$condition]));
        $response = $this->toggleActiveApplicationConditionUseCase->execute($condition->id);

        static::assertEquals(true, $response->active);
        static::assertIsObject($condition, $response);
        Bus::assertDispatched(SendHook::class);
    }
}
