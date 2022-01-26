<?php

namespace App\UseCases\ApplicationConditions;

use App\Exceptions\BusinessException;
use App\HttpServices\Hooks\DTO\HookData;
use App\Jobs\SendHook;
use App\Modules\Merchants\Models\Condition;
use App\Services\Alifshop\AlifshopService;
use Illuminate\Support\Facades\Cache;

class ToggleActiveApplicationConditionUseCase
{
    public function execute(int $condition_id , $user)
    {
        $condition = Condition::query()->find($condition_id);

        if ($condition === null)
        {
            throw new BusinessException('Условие не найдено' , 'condition_not_found' , 404);
        }

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

        $alifshopService = new AlifshopService;
        $alifshopService->storeOrUpdateConditions($merchant->company_id, $conditions);

        Cache::tags($merchant->id)->flush();

        return $condition;
    }
}
