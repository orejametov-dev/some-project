<?php

declare(strict_types=1);

namespace App\UseCases\ApplicationConditions;

use Alifuz\Utils\Gateway\Entities\Auth\GatewayAuthUser;
use App\HttpRepositories\Alifshop\AlifshopHttpRepository;
use App\HttpRepositories\Hooks\DTO\HookData;
use App\Jobs\SendHook;
use App\Models\Condition;
use App\Repositories\ApplicationConditionRepository;
use App\UseCases\Cache\FlushCacheUseCase;
use App\UseCases\Merchants\FindMerchantByIdUseCase;
use Illuminate\Support\Collection;

class ToggleActiveApplicationConditionUseCase
{
    public function __construct(
        private AlifshopHttpRepository $alifshopHttpRepository,
        private FindConditionByIdUseCase $findConditionUseCase,
        private FlushCacheUseCase $flushCacheUseCase,
        private GatewayAuthUser $gatewayAuthUser,
        private ApplicationConditionRepository $applicationConditionRepository,
        private FindMerchantByIdUseCase $findMerchantByIdUseCase,
    ) {
    }

    public function execute(int $condition_id) : Condition
    {
        $condition = $this->findConditionUseCase->execute($condition_id);
        $condition->active = !$condition->active;
        $this->applicationConditionRepository->save($condition);

        $merchant = $this->findMerchantByIdUseCase->execute($condition->merchant_id);

        SendHook::dispatch(new HookData(
            service: 'merchants',
            hookable_type: $merchant->getTable(),
            hookable_id: $merchant->id,
            created_from_str: 'PRM',
            created_by_id: $this->gatewayAuthUser->getId(),
            body: 'Изменено условие',
            keyword: 'id: ' . $condition->id . ' ' . $condition->title . ' на ' . (($condition->active === true) ? 'активный' : 'не активный'),
            action: 'update',
            class: 'warning',
            action_at: null,
            created_by_str: $this->gatewayAuthUser->getName(),
        ));

        $conditions = $this->applicationConditionRepository->getByActiveTruePostAlifshopTrueWithMerchantId($merchant->id);

        $item = [];
        foreach ($conditions as $condition_item) {
            $item[] = [
                'id' => $condition_item->id,
                'commission' => $condition_item->commission,
                'duration' => $condition_item->duration,
                'event_id' => $condition_item->event_id,
            ];
        }

        $this->alifshopHttpRepository->storeOrUpdateConditions($merchant->company_id, Collection::make($item));
        $this->flushCacheUseCase->execute($merchant->id);

        return $condition;
    }
}
