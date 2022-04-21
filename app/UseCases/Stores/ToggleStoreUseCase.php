<?php

namespace App\UseCases\Stores;

use Alifuz\Utils\Gateway\Entities\Auth\GatewayAuthUser;
use App\Exceptions\NotFoundException;
use App\Models\Store;
use App\Models\StoreActivity;
use App\Repositories\ActivityReasonRepository;
use App\Repositories\StoreActivityRepository;
use App\Repositories\StoreRepository;
use App\UseCases\Cache\FlushCacheUseCase;

class ToggleStoreUseCase
{
    public function __construct(
        private GatewayAuthUser $gatewayAuthUser,
        private FindStoreByIdUseCase $findStoresUseCase,
        private FlushCacheUseCase $flushCacheUseCase,
        private ActivityReasonRepository $activityReasonRepository,
        private StoreRepository $storeRepository,
        private StoreActivityRepository $storeActivityRepository
    ) {
    }

    public function execute(int $id, int $activity_reason_id): Store
    {
        $active_reason = $this->activityReasonRepository->getByIdWithType('STORE', $activity_reason_id);

        if ($active_reason === null) {
            throw new  NotFoundException('Причина не найден');
        }

        $store = $this->findStoresUseCase->execute($id);
        $store->active = !$store->active;
        $this->storeRepository->save($store);

        $store_activity = new StoreActivity();
        $store_activity->active = $store->active;
        $store_activity->store_id = $store->id;
        $store_activity->activity_reason_id = $active_reason->id;
        $store_activity->store_type = __CLASS__;
        $store_activity->created_by_id = $this->gatewayAuthUser->getId();
        $store_activity->created_by_name = $this->gatewayAuthUser->getName();

        $this->storeActivityRepository->save($store_activity);

        $this->flushCacheUseCase->execute($store->merchant_id);

        return $store;
    }
}
