<?php

namespace App\UseCases\ApplicationConditions;

use App\DTOs\Conditions\UpdateConditionDTO;
use App\Exceptions\BusinessException;
use App\HttpRepositories\Core\CoreHttpRepository;
use App\HttpServices\Hooks\DTO\HookData;
use App\Jobs\SendHook;
use App\Modules\Merchants\Models\Store;
use App\UseCases\Cache\FlushCacheUseCase;

class UpdateApplicationConditionUseCase
{
    public function __construct(
        private CoreHttpRepository $coreHttpRepository,
        private FindConditionUseCase $findConditionUseCase,
        private FlushCacheUseCase $flushCacheUseCase
    )
    {
    }

    public function execute(int $condition_id, UpdateConditionDTO $updateConditionDTO)
    {
        $condition = $this->findConditionUseCase->execute($condition_id);

        if ($this->coreHttpRepository->checkApplicationToExistConditionId($condition_id)) {
            return response()->json(['message' => 'Условие не может быть изменено'], 400);
        }

        $merchant = $condition->merchant;

        $store_ids = $conditionDTO->store_ids ?? [];

        $merchant_stores = Store::query()
            ->where('merchant_id' , $merchant->id)
            ->where('active' , true)
            ->get();

        if (array_diff($store_ids ,$merchant_stores->whereIn('id' , $store_ids)->pluck('id')->toArray()) != null) {
            throw new BusinessException('Указан не правильно магазин' , 'wrong_store' , 400);
        }

        $main_store = $merchant_stores->where('is_main', true)->first();

        if ($main_store === null) {
            throw new BusinessException('У данного мерчанта нет основного магазина ' . $merchant->name, 'main_store_not_exists', 400);
        }

        if (in_array($main_store->id, $store_ids) === false) {
            $store_ids[] = $main_store->id;
        }

        $condition->stores()->detach();
        $condition->stores()->attach($store_ids);

        $condition->duration = $updateConditionDTO->duration;
        $condition->commission = $updateConditionDTO->commission;
        $condition->discount = $updateConditionDTO->discount;
        $condition->is_special = $store_ids != null ?? false;
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

        $this->flushCacheUseCase->execute($merchant->id);

        return $condition;
    }
}
