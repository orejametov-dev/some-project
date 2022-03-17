<?php

declare(strict_types=1);

namespace App\UseCases\ApplicationConditions;

use Alifuz\Utils\Gateway\Entities\Auth\GatewayAuthUser;
use App\HttpRepositories\Alifshop\AlifshopHttpRepository;
use App\HttpServices\Hooks\DTO\HookData;
use App\Jobs\SendHook;
use App\Models\Condition;
use App\UseCases\Cache\FlushCacheUseCase;

class ToggleActiveApplicationConditionUseCase
{
    public function __construct(
        private AlifshopHttpRepository $alifshopHttpRepository,
        private FindConditionByIdUseCase $findConditionUseCase,
        private FlushCacheUseCase $flushCacheUseCase,
        private GatewayAuthUser $gatewayAuthUser
    ) {
    }

    public function execute(int $condition_id) : Condition
    {
        $condition = $this->findConditionUseCase->execute($condition_id);
        $condition->active = !$condition->active;
        $condition->save();

        $merchant = $condition->merchant;

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

        $merchant->load(['application_conditions' => function ($q) {
            $q->active();
        }]);

        $conditions = $merchant->application_conditions->where('post_alifshop', true)->map(function ($item) {
            return [
                'id' => $item->id,
                'commission' => $item->commission,
                'duration' => $item->duration,
                'event_id' => $item->event_id,
            ];
        });

        $this->alifshopHttpRepository->storeOrUpdateConditions($merchant->company_id, $conditions);
        $this->flushCacheUseCase->execute($merchant->id);

        return $condition;
    }
}
