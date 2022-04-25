<?php

namespace Tests\Unit\ApplicationConditions;

use Alifuz\Utils\Gateway\Entities\Auth\GatewayAuthUser;
use App\DTOs\Conditions\StoreConditionDTO;
use App\Exceptions\BusinessException;
use App\Jobs\SendHook;
use App\Models\Merchant;
use App\Models\Store;
use App\Repositories\ApplicationConditionRepository;
use App\Repositories\SpecialStoreConditionRepository;
use App\Repositories\StoreRepository;
use App\UseCases\ApplicationConditions\CheckStartedAtAndFinishedAtConditionUseCase;
use App\UseCases\ApplicationConditions\StoreApplicationConditionUseCase;
use App\UseCases\Cache\FlushCacheUseCase;
use App\UseCases\Merchants\FindMerchantByIdUseCase;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class StoreApplicationConditionUseCaseTest extends TestCase
{
    private FindMerchantByIdUseCase $findMerchantByIdUseCase;
    private StoreRepository $storeRepository;
    private ApplicationConditionRepository $applicationConditionRepository;
    private SpecialStoreConditionRepository $specialStoreConditionRepository;
    private StoreApplicationConditionUseCase $storeApplicationConditionUseCase;

    public function setUp(): void
    {
        parent::setUp();
        $checkStartedAtAndFinishedAtConditionUseCase = $this->createMock(CheckStartedAtAndFinishedAtConditionUseCase::class);
        $flushCacheUseCase = $this->createMock(FlushCacheUseCase::class);
        $gatewayAuthUser = $this->createMock(GatewayAuthUser::class);
        $this->storeRepository = $this->createMock(StoreRepository::class);
        $this->findMerchantByIdUseCase = $this->createMock(FindMerchantByIdUseCase::class);
        $this->storeRepository = $this->createMock(StoreRepository::class);
        $this->applicationConditionRepository = $this->createMock(ApplicationConditionRepository::class);
        $this->specialStoreConditionRepository = $this->createMock(SpecialStoreConditionRepository::class);
        $this->storeApplicationConditionUseCase = new StoreApplicationConditionUseCase(
            checkStartedAtAndFinishedAtConditionUseCase: $checkStartedAtAndFinishedAtConditionUseCase,
            findMerchantUseCase: $this->findMerchantByIdUseCase,
            flushCacheUseCase: $flushCacheUseCase,
            gatewayAuthUser: $gatewayAuthUser,
            storeRepository: $this->storeRepository,
            applicationConditionRepository: $this->applicationConditionRepository,
            specialStoreConditionRepository: $this->specialStoreConditionRepository,
        );
    }

    public function testWrongStore()
    {
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

        $conditionDTO = StoreConditionDTO::fromArray([
            'merchant_id' => 1,
            'store_ids' => [1, 2, 3, 4],
            'duration' => 1,
            'commission' => 1,
            'special_offer' => 'test',
            'event_id' => 1,
            'discount' => 1,
            'post_merchant' => 1,
            'post_alifshop' => 1,
            'started_at' => null,
            'finished_at' => null,
        ]);

        $this->expectException(BusinessException::class);
        $this->findMerchantByIdUseCase->method('execute')->willReturn($merchant);
        $this->storeRepository->method('getByActiveTrueMerchantId')->willReturn(Collection::make([$store, $store2, $store3]));
        $this->storeRepository->method('getByIsMainTrueMerchantId')->willReturn($store);
        $this->storeApplicationConditionUseCase->execute($conditionDTO);
    }

    public function testMainStoreNotExists()
    {
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

        $conditionDTO = StoreConditionDTO::fromArray([
            'merchant_id' => 1,
            'store_ids' => [1, 2, 3],
            'duration' => 1,
            'commission' => 1,
            'special_offer' => 'test',
            'event_id' => 1,
            'discount' => 1,
            'post_merchant' => 1,
            'post_alifshop' => 1,
            'started_at' => null,
            'finished_at' => null,
        ]);

        $this->expectException(BusinessException::class);
        $this->findMerchantByIdUseCase->method('execute')->willReturn($merchant);
        $this->storeRepository->method('getByActiveTrueMerchantId')->willReturn(Collection::make([$store, $store2, $store3]));
        $this->storeRepository->method('getByIsMainTrueMerchantId')->willReturn(null);
        $this->storeApplicationConditionUseCase->execute($conditionDTO);
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

        $conditionDTO = StoreConditionDTO::fromArray([
            'merchant_id' => 1,
            'store_ids' => [1, 2, 3],
            'duration' => 1,
            'commission' => 1,
            'special_offer' => 'test',
            'event_id' => 1,
            'discount' => 1,
            'post_merchant' => 1,
            'post_alifshop' => 1,
            'started_at' => null,
            'finished_at' => null,
        ]);

        $this->findMerchantByIdUseCase->method('execute')->willReturn($merchant);
        $this->storeRepository->method('getByActiveTrueMerchantId')->willReturn(Collection::make([$store, $store2, $store3]));
        $this->storeRepository->method('getByIsMainTrueMerchantId')->willReturn($store);
        $response = $this->storeApplicationConditionUseCase->execute($conditionDTO);

        static::assertIsObject($response);
        Bus::assertDispatched(SendHook::class);
    }
}
