<?php

namespace App\UseCases\Stores;

use Alifuz\Utils\Gateway\Entities\Auth\GatewayAuthUser;
use App\Exceptions\BusinessException;
use App\Modules\Merchants\Models\ActivityReason;
use App\UseCases\Cache\FlushCacheUseCase;

class ToggleStoresUseCase
{
    public function __construct(
        private GatewayAuthUser $gatewayAuthUser,
        private FindStoresUseCase $findStoresUseCase,
        private FlushCacheUseCase $flushCacheUseCase
    ) {
    }

    public function execute(int $id, int $activity_reason_id)
    {
        $active_reason = ActivityReason::where('type', 'STORE')->find($activity_reason_id);

        if ($active_reason === null) {
            throw new  BusinessException('Причина не найден', 'object_not_found', 404);
        }

        $store = $this->findStoresUseCase->execute($id);
        $store->active = !$store->active;
        $store->save();

        $store->activity_reasons()->attach($active_reason, [
            'active' => $store->active,
            'created_by_id' => $this->gatewayAuthUser->getId(),
            'created_by_name' => $this->gatewayAuthUser->getName(),
        ]);

        $this->flushCacheUseCase->execute($store->merchant_id);

        return $store;
    }
}
