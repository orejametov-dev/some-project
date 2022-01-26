<?php

namespace App\UseCases\ApplicationConditions;

use App\Exceptions\BusinessException;
use App\HttpServices\Core\CoreService;
use App\HttpServices\Hooks\DTO\HookData;
use App\Jobs\SendHook;
use App\Modules\Merchants\DTO\Conditions\UpdateConditionDTO;
use App\Modules\Merchants\Models\Condition;
use Illuminate\Support\Facades\Cache;

class UpdateApplicationConditionUseCase
{
    public function execute(int $condition_id , UpdateConditionDTO $updateConditionDTO)
    {
        /** @var Condition $condition */
        $condition = Condition::query()->find($condition_id);

        if ($condition === null)
        {
            throw new BusinessException('Условие не найдено' , 'condition_not_found' , 404);
        }

        $applications = CoreService::getApplicationConditionId($condition_id);

        if ($applications) {
            return response()->json(['message' => 'Условие не может быть изменено'], 400);
        }
        $merchant = $condition->merchant;

        $merchant_stores = $merchant->stores()->active()->get();

        $store_ids = $updateConditionDTO->store_ids ?? [];
        foreach ($store_ids as $id) {
            if (!$merchant_stores->where('id', $id)->first()) {
                return response()->json(['message' => 'Указан не правильный магазин'], 400);
            }
        }

        $main_store = $merchant_stores->where('is_main')->first();

        if (!$main_store) {
            throw new BusinessException('У данного мерчанта нет основного магазина ' . $merchant->name, 'main_store_not_exists', 400);
        }

        if (!in_array($main_store->id, $store_ids)) {
            $store_ids[] = $main_store->id;
        }

        $condition->stores()->detach();
        $condition->stores()->attach($store_ids);

        $condition->duration = $updateConditionDTO->duration;
        $condition->commission = $updateConditionDTO->commission;
        $condition->discount = $updateConditionDTO->discount;
        $condition->is_special = !empty($store_ids) ?? false;
        $condition->special_offer = $updateConditionDTO->special_offer;
        $condition->event_id = $updateConditionDTO->event_id;

        $condition->save();

        SendHook::dispatch(new HookData(
            service: 'merchants',
            hookable_type: $merchant->getTable(),
            hookable_id: $merchant->id,
            created_from_str: 'PRM',
            created_by_id: $updateConditionDTO->user_id,
            body: 'Изменено условие',
            keyword: 'id: ' . $condition->id . ' ' . $condition->title,
            action: 'update',
            class: 'warning',
            action_at: null,
            created_by_str: $updateConditionDTO->user_name,
        ));

        Cache::tags($merchant->id)->flush();

        return $condition;
    }
}
