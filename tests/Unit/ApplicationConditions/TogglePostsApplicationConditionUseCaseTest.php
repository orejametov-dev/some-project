<?php

namespace Tests\Unit\ApplicationConditions;

use App\Exceptions\BusinessException;
use App\HttpRepositories\Alifshop\AlifshopHttpRepository;
use App\Models\Condition;
use App\Models\Merchant;
use App\Models\Store;
use App\Repositories\ApplicationConditionRepository;
use App\Repositories\StoreRepository;
use App\UseCases\ApplicationConditions\FindConditionByIdUseCase;
use App\UseCases\ApplicationConditions\TogglePostsApplicationConditionUseCase;
use App\UseCases\Cache\FlushCacheUseCase;
use App\UseCases\Merchants\FindMerchantByIdUseCase;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;

class TogglePostsApplicationConditionUseCaseTest extends TestCase
{
    private FindConditionByIdUseCase $findConditionByIdUseCase;
    private ApplicationConditionRepository $applicationConditionRepository;
    private FindMerchantByIdUseCase $findMerchantByIdUseCase;
    private StoreRepository $storeRepository;
    private TogglePostsApplicationConditionUseCase $togglePostsApplicationConditionUseCase;

    public function setUp(): void
    {
        parent::setUp();
        $alifshopHttpRepository = $this->createMock(AlifshopHttpRepository::class);
        $this->findConditionByIdUseCase = $this->createMock(FindConditionByIdUseCase::class);
        $flushCacheUseCase = $this->createMock(FlushCacheUseCase::class);
        $this->applicationConditionRepository = $this->createMock(ApplicationConditionRepository::class);
        $this->findMerchantByIdUseCase = $this->createMock(FindMerchantByIdUseCase::class);
        $this->storeRepository = $this->createMock(StoreRepository::class);
        $this->togglePostsApplicationConditionUseCase = new TogglePostsApplicationConditionUseCase(
            alifshopHttpRepository: $alifshopHttpRepository,
            findConditionByIdUseCase: $this->findConditionByIdUseCase,
            flushCacheUseCase: $flushCacheUseCase,
            applicationConditionRepository: $this->applicationConditionRepository,
            findMerchantByIdUseCase: $this->findMerchantByIdUseCase,
            storeRepository: $this->storeRepository,
        );
    }

    public function testMainStoreNotExists()
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

        $this->expectException(BusinessException::class);
        $this->findConditionByIdUseCase->method('execute')->willReturn($condition);
        $this->findMerchantByIdUseCase->method('execute')->willReturn($merchant);
        $this->storeRepository->method('getByIsMainTrueMerchantId')->willReturn(null);
        $this->togglePostsApplicationConditionUseCase->execute($condition->id, true, true);
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
        $condition->post_merchant = false;
        $condition->post_alifshop = false;
        $condition->active = false;

        $merchant = new Merchant();
        $merchant->id = 1;
        $merchant->name = 'test';
        $merchant->legal_name = 'test';
        $merchant->legal_name_prefix = 'LLC';
        $merchant->token = 'test';
        $merchant->maintainer_id = 1;
        $merchant->company_id = 1;

        $store = new Store();
        $store->id = 1;
        $store->name = 'test';
        $store->merchant_id = 1;
        $store->is_main = true;
        $store->address = 'test';
        $store->lat = 1;
        $store->long = 1;
        $store->phone = 1;
        $store->active = true;

        $this->findConditionByIdUseCase->method('execute')->willReturn($condition);
        $this->findMerchantByIdUseCase->method('execute')->willReturn($merchant);
        $this->storeRepository->method('getByIsMainTrueMerchantId')->willReturn($store);
        $this->applicationConditionRepository->method('getByActiveTruePostAlifshopTrueWithMerchantId')->willReturn(Collection::make([$condition]));
        $response = $this->togglePostsApplicationConditionUseCase->execute($condition->id, true, true);

        static::assertIsObject($condition, $response);
        static::assertEquals(true, $response->post_alifshop);
        static::assertEquals(true, $response->post_merchant);
    }
}
