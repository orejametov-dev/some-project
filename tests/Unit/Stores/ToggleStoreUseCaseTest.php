<?php

namespace Tests\Unit\Stores;

use Alifuz\Utils\Gateway\Entities\Auth\GatewayAuthUser;
use App\Exceptions\NotFoundException;
use App\Models\ActivityReason;
use App\Models\Store;
use App\Repositories\ActivityReasonRepository;
use App\Repositories\StoreActivityRepository;
use App\Repositories\StoreRepository;
use App\UseCases\Cache\FlushCacheUseCase;
use App\UseCases\Stores\FindStoreByIdUseCase;
use App\UseCases\Stores\ToggleStoreUseCase;
use PHPUnit\Framework\TestCase;

class ToggleStoreUseCaseTest extends TestCase
{
    private FindStoreByIdUseCase $findStoresUseCase;
    private ActivityReasonRepository $activityReasonRepository;
    private ToggleStoreUseCase $toggleStoreUseCase;

    public function setUp(): void
    {
        parent::setUp();

        $gatewayAuthUser = $this->createMock(GatewayAuthUser::class);
        $this->findStoresUseCase = $this->createMock(FindStoreByIdUseCase::class);
        $flushCacheUseCase = $this->createMock(FlushCacheUseCase::class);
        $this->activityReasonRepository = $this->createMock(ActivityReasonRepository::class);
        $storeRepository = $this->createMock(StoreRepository::class);
        $storeActivityRepository = $this->createMock(StoreActivityRepository::class);
        $this->toggleStoreUseCase = new ToggleStoreUseCase(
            gatewayAuthUser: $gatewayAuthUser,
            findStoresUseCase: $this->findStoresUseCase,
            flushCacheUseCase: $flushCacheUseCase,
            activityReasonRepository: $this->activityReasonRepository,
            storeRepository: $storeRepository,
            storeActivityRepository: $storeActivityRepository,
        );
    }

    public function testNotFound()
    {
        $this->expectException(NotFoundException::class);
        $this->activityReasonRepository->method('getByIdWithType')->willReturn(null);
        $this->toggleStoreUseCase->execute(1, 1);
    }

    public function testSuccess()
    {
        $activity_reason = new ActivityReason();
        $activity_reason->id = 1;
        $activity_reason->body = 'test';
        $activity_reason->type = 'STORE';
        $activity_reason->active = true;

        $store = new Store();
        $store->id = 1;
        $store->name = 'test';
        $store->merchant_id = 1;
        $store->is_main = true;
        $store->address = 'test';
        $store->lat = 1;
        $store->long = 1;
        $store->phone = 1;
        $store->active = false;

        $this->activityReasonRepository->method('getByIdWithType')->willReturn($activity_reason);
        $this->findStoresUseCase->method('execute')->willReturn($store);
        $response = $this->toggleStoreUseCase->execute($store->id, $activity_reason->id);

        static::assertIsObject($store, $response);
        static::assertEquals(true, $response->active);
    }
}
