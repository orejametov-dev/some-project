<?php

namespace App\UseCases\ApplicationConditions;

use App\Exceptions\BusinessException;
use App\HttpRepositories\Alifshop\AlifshopHttpRepository;
use App\HttpServices\Hooks\DTO\HookData;
use App\Jobs\SendHook;
use App\Modules\Merchants\Models\Condition;
use App\UseCases\Cache\FlushCacheUseCase;
use Illuminate\Support\Facades\Cache;

class ToggleActiveApplicationConditionUseCase
{
    public function __construct(
        private AlifshopHttpRepository $alifshopHttpRepository,
        private FindConditionUseCase $findConditionUseCase,
        private FlushCacheUseCase $flushCacheUseCase
    )
    {
    }

    public function execute(int $condition_id , $user)
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
            created_by_id: $user->id,
            body: 'Изменено условие',
            keyword: 'id: ' . $condition->id . ' ' . $condition->title . ' на ' . ($condition->active) ? 'активный' : 'не активный',
            action: 'update',
            class: 'warning',
            action_at: null,
            created_by_str: $user->name,
        ));


        $merchant->load(['application_conditions' => function ($q) {
            $q->active();
        }]);

        $conditions = $merchant->application_conditions->where('post_alifshop', true)->map(function ($item) {
            return [
                'id' => $item->id,
                'commission' => $item->commission,
                'duration' => $item->duration,
                'event_id' => $item->event_id
            ];
        });

        $this->alifshopHttpRepository->storeOrUpdateConditions($merchant->company_id, $conditions);
        $this->flushCacheUseCase->execute($merchant->id);

        return $condition;
    }
}
