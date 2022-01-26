<?php

namespace App\UseCases\ApplicationConditions;

use App\Exceptions\BusinessException;
use App\HttpServices\Hooks\DTO\HookData;
use App\Jobs\SendHook;
use App\Modules\Merchants\DTO\Conditions\StoreConditionDTO;
use App\Modules\Merchants\Models\Condition;
use App\Modules\Merchants\Models\Merchant;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class StoreApplicationConditionUseCase
{
    public function __construct(
        private CheckStartedAtAndFinishedAtConditionUseCase $checkStartedAtAndFinishedAtConditionUseCase
    )
    {
    }

    public function execute(StoreConditionDTO $conditionDTO)
    {
        /** @var Merchant $merchant */
        $merchant = Merchant::query()->find($conditionDTO->merchant_id);

        if ($merchant === null) {
            throw new BusinessException('мерчант не найден', 'merchant_not_found', 404);
        }

        $merchant_stores = $merchant->stores()->active()->get();

        $store_ids = $conditionDTO->store_ids ?? [];
        foreach ($store_ids as $id) {
            if (!$merchant_stores->where('id', $id)->first()) {
                return response()->json(['message' => 'Указан не правильный магазин'], 400);
            }
        }

        $main_store = $merchant_stores->where('is_main')->first();

        if (!$main_store) {
            throw new BusinessException('У данного мерчанта нет основного магазина ' . $merchant->name, 'main_store_not_exists', 400);
        }

        if ($conditionDTO->post_alifshop and !in_array($main_store->id, $store_ids)) {
            $store_ids[] = $main_store->id;
        }

        $condition = new Condition();
        $condition->duration = $conditionDTO->duration;
        $condition->commission = $conditionDTO->commission;
        $condition->discount = $conditionDTO->discount;
        $condition->is_special = !empty($store_ids) ?? false;
        $condition->special_offer = $conditionDTO->special_offer;
        $condition->event_id = $conditionDTO->event_id;
        $condition->post_merchant = $conditionDTO->post_merchant;
        $condition->post_alifshop = $conditionDTO->post_alifshop;
        $condition->merchant()->associate($merchant);
        $condition->store_id = $main_store->id;

        $this->checkStartedAtAndFinishedAtConditionUseCase->execute($conditionDTO->started_at, $conditionDTO->finished_at);

        $condition->started_at = $conditionDTO->started_at ? Carbon::parse($conditionDTO->started_at)->format('Y-m-d') : null;
        $condition->finished_at = $conditionDTO->finished_at ? Carbon::parse($conditionDTO->finished_at)->format('Y-m-d') : null;

        if ($conditionDTO->started_at === null) {
            $condition->active = true;
        }

        $condition->save();

        if ($store_ids) {
            $condition->stores()->attach($store_ids);
        }

        SendHook::dispatch(new HookData(
            service: 'merchants',
            hookable_type: $merchant->getTable(),
            hookable_id: $merchant->id,
            created_from_str: 'PRM',
            created_by_id: $conditionDTO->user_id,
            body: 'Создано условие',
            keyword: 'id: ' . $condition->id . ' ' . $condition->title,
            action: 'create',
            class: 'info',
            action_at: null,
            created_by_str: $conditionDTO->user_name,
        ));

        Cache::tags($merchant->id)->flush();

        return $condition->load('stores');
    }
}
