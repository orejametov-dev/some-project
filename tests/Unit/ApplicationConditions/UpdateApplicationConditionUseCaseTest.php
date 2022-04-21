<?php

namespace Tests\Unit\ApplicationConditions;

use Alifuz\Utils\Gateway\Entities\Auth\GatewayAuthUser;
use App\DTOs\Conditions\UpdateConditionDTO;
use App\Exceptions\BusinessException;
use App\HttpRepositories\Core\CoreHttpRepository;
use App\Jobs\SendHook;
use App\Models\Condition;
use App\Models\Merchant;
use App\Models\SpecialStoreCondition;
use App\Models\Store;
use App\Repositories\ApplicationConditionRepository;
use App\Repositories\SpecialStoreConditionRepository;
use App\Repositories\StoreRepository;
use App\UseCases\ApplicationConditions\FindConditionByIdUseCase;
use App\UseCases\ApplicationConditions\UpdateApplicationConditionUseCase;
use App\UseCases\Cache\FlushCacheUseCase;
use App\UseCases\Merchants\FindMerchantByIdUseCase;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class UpdateApplicationConditionUseCaseTest extends TestCase
{
    private CoreHttpRepository $coreHttpRepository;
    private FindConditionByIdUseCase $findConditionByIdUseCase;
    private StoreRepository $storeRepository;
    private SpecialStoreConditionRepository $specialStoreConditionRepository;
    private FindMerchantByIdUseCase $findMerchantByIdUseCase;
    private ApplicationConditionRepository $applicationConditionRepository;
    private UpdateApplicationConditionUseCase $updateApplicationConditionUseCase;

    public function setUp(): void
    {
        parent::setUp();

        $this->findConditionByIdUseCase = $this->createMock(FindConditionByIdUseCase::class);
        $flushCacheUseCase = $this->createMock(FlushCacheUseCase::class);
        $gatewayAuthUser = $this->createMock(GatewayAuthUser::class);
        $this->applicationConditionRepository = $this->createMock(ApplicationConditionRepository::class);
        $this->findMerchantByIdUseCase = $this->createMock(FindMerchantByIdUseCase::class);
        $this->storeRepository = $this->createMock(StoreRepository::class);
        $this->applicationConditionRepository = $this->createMock(ApplicationConditionRepository::class);
        $this->specialStoreConditionRepository = $this->createMock(SpecialStoreConditionRepository::class);
        $this->coreHttpRepository = $this->createMock(CoreHttpRepository::class);
        $this->updateApplicationConditionUseCase = new UpdateApplicationConditionUseCase(
            coreHttpRepository: $this->coreHttpRepository,
            findConditionByIdUseCase: $this->findConditionByIdUseCase,
            flushCacheUseCase: $flushCacheUseCase,
            gatewayAuthUser: $gatewayAuthUser,
            storeRepository: $this->storeRepository,
            specialStoreConditionRepository: $this->specialStoreConditionRepository,
            applicationConditionRepository:  $this->applicationConditionRepository,
            findMerchantByIdUseCase: $this->findMerchantByIdUseCase
        );
    }

    public function testNotAllowed()
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

        $conditionDTO = UpdateConditionDTO::fromArray([
            'store_ids' => [1, 2, 3],
            'duration' => 2,
            'commission' => 2,
            'special_offer' => 'test',
            'event_id' => 2,
            'discount' => 2,
        ]);

        $this->expectException(BusinessException::class);
        $this->findConditionByIdUseCase->method('execute')->willReturn($condition);

        $this->coreHttpRepository->method('checkApplicationToExistByConditionId')->willReturn(true);
        $this->updateApplicationConditionUseCase->execute($condition->id, $conditionDTO);
    }

    public function testWrongStore()
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

        $store2 = new Store();
        $store2->id = 2;
        $store2->name = 'test';
        $store2->merchant_id = 2;
        $store2->is_main = true;
        $store2->address = 'test';
        $store2->lat = 2;
        $store2->long = 2;
        $store2->phone = 2;
        $store2->active = true;

        $store3 = new Store();
        $store3->id = 3;
        $store3->name = 'test';
        $store3->merchant_id = 3;
        $store3->is_main = true;
        $store3->address = 'test';
        $store3->lat = 3;
        $store3->long = 3;
        $store3->phone = 3;
        $store3->active = true;

        $conditionDTO = UpdateConditionDTO::fromArray([
            'store_ids' => [1, 2, 3, 4],
            'duration' => 1,
            'commission' => 1,
            'special_offer' => 'test',
            'event_id' => 1,
            'discount' => 1,
        ]);

        $this->expectException(BusinessException::class);
        $this->findConditionByIdUseCase->method('execute')->willReturn($condition);
        $this->findMerchantByIdUseCase->method('execute')->willReturn($merchant);
        $this->coreHttpRepository->method('checkApplicationToExistByConditionId')->willReturn(false);
        $this->storeRepository->method('getByActiveTrueMerchantId')->willReturn(Collection::make([$store, $store2, $store3]));
        $this->updateApplicationConditionUseCase->execute($condition->id, $conditionDTO);
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

        $store2 = new Store();
        $store2->id = 2;
        $store2->name = 'test';
        $store2->merchant_id = 2;
        $store2->is_main = true;
        $store2->address = 'test';
        $store2->lat = 2;
        $store2->long = 2;
        $store2->phone = 2;
        $store2->active = true;

        $store3 = new Store();
        $store3->id = 3;
        $store3->name = 'test';
        $store3->merchant_id = 3;
        $store3->is_main = true;
        $store3->address = 'test';
        $store3->lat = 3;
        $store3->long = 3;
        $store3->phone = 3;
        $store3->active = true;

        $conditionDTO = UpdateConditionDTO::fromArray([
            'store_ids' => [1, 2, 3],
            'duration' => 1,
            'commission' => 1,
            'special_offer' => 'test',
            'event_id' => 1,
            'discount' => 1,
        ]);

        $this->expectException(BusinessException::class);
        $this->findConditionByIdUseCase->method('execute')->willReturn($condition);
        $this->findMerchantByIdUseCase->method('execute')->willReturn($merchant);
        $this->coreHttpRepository->method('checkApplicationToExistByConditionId')->willReturn(false);
        $this->storeRepository->method('getByActiveTrueMerchantId')->willReturn(Collection::make([$store, $store2, $store3]));
        $this->storeRepository->method('getByIsMainTrueMerchantId')->willReturn(null);
        $this->updateApplicationConditionUseCase->execute($condition->id, $conditionDTO);
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

        $store2 = new Store();
        $store2->id = 2;
        $store2->name = 'test';
        $store2->merchant_id = 2;
        $store2->is_main = true;
        $store2->address = 'test';
        $store2->lat = 2;
        $store2->long = 2;
        $store2->phone = 2;
        $store2->active = true;

        $store3 = new Store();
        $store3->id = 3;
        $store3->name = 'test';
        $store3->merchant_id = 3;
        $store3->is_main = true;
        $store3->address = 'test';
        $store3->lat = 3;
        $store3->long = 3;
        $store3->phone = 3;
        $store3->active = true;

        $special_store_condition = new SpecialStoreCondition();
        $special_store_condition->id = 1;
        $special_store_condition->store_id = 1;
        $special_store_condition->condition_id = 1;

        $special_store_condition2 = new SpecialStoreCondition();
        $special_store_condition2->id = 2;
        $special_store_condition2->store_id = 2;
        $special_store_condition2->condition_id = 2;

        $conditionDTO = UpdateConditionDTO::fromArray([
            'store_ids' => [1, 2, 3],
            'duration' => 2,
            'commission' => 2,
            'special_offer' => 'test-update',
            'event_id' => 2,
            'discount' => 2,
        ]);

        $this->findConditionByIdUseCase->method('execute')->willReturn($condition);
        $this->findMerchantByIdUseCase->method('execute')->willReturn($merchant);
        $this->coreHttpRepository->method('checkApplicationToExistByConditionId')->willReturn(false);
        $this->specialStoreConditionRepository->method('getByConditionId')->willReturn(Collection::make([$special_store_condition, $special_store_condition2]));
        $this->storeRepository->method('getByActiveTrueMerchantId')->willReturn(Collection::make([$store, $store2, $store3]));
        $this->storeRepository->method('getByIsMainTrueMerchantId')->willReturn($store);
        $response = $this->updateApplicationConditionUseCase->execute($condition->id, $conditionDTO);

        static::assertEquals($conditionDTO->getDuration(), $response->duration);
        static::assertEquals($conditionDTO->getCommission(), $response->commission);
        static::assertEquals($conditionDTO->getSpecialOffer(), $response->special_offer);
        static::assertEquals($conditionDTO->getEventId(), $response->event_id);
        static::assertEquals($conditionDTO->getDiscount(), $response->discount);
        Bus::assertDispatched(SendHook::class);
    }
}
