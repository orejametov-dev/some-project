<?php

declare(strict_types=1);

namespace App\UseCases\ApplicationConditions;

use Alifuz\Utils\Gateway\Entities\Auth\GatewayAuthUser;
use App\DTOs\Conditions\StoreConditionDTO;
use App\Exceptions\BusinessException;
use App\HttpRepositories\Hooks\DTO\HookData;
use App\Jobs\SendHook;
use App\Modules\Merchants\Models\Condition;
use App\Modules\Merchants\Models\Store;
use App\UseCases\Cache\FlushCacheUseCase;
use App\UseCases\Merchants\FindMerchantByIdUseCase;
use Carbon\Carbon;

class StoreApplicationConditionUseCase
{
    public function __construct(
        private CheckStartedAtAndFinishedAtConditionUseCase $checkStartedAtAndFinishedAtConditionUseCase,
        private FindMerchantByIdUseCase $findMerchantUseCase,
        private FlushCacheUseCase $flushCacheUseCase,
        private GatewayAuthUser $gatewayAuthUser
    ) {
    }

    public function execute(StoreConditionDTO $conditionDTO) : Condition
    {
        $merchant = $this->findMerchantUseCase->execute($conditionDTO->merchant_id);

        $store_ids = $conditionDTO->store_ids ?? [];

        $merchant_stores = Store::query()
            ->where('merchant_id', $merchant->id)
            ->where('active', true)
            ->get();

        $main_store = $merchant_stores->where('is_main', true)->first();

        if (array_diff($store_ids, $merchant_stores->whereIn('id', $store_ids)->pluck('id')->toArray()) != null) {
            throw new BusinessException('Указан не правильно магазин', 'wrong_store', 400);
        }

        if ($main_store === null) {
            throw new BusinessException('У данного мерчанта нет основного магазина ' . $merchant->name, 'main_store_not_exists', 400);
        }

        if ($conditionDTO->post_alifshop === true and in_array($main_store->id, $store_ids) === false) {
            $store_ids[] = $main_store->id;
        }

        $this->checkStartedAtAndFinishedAtConditionUseCase->execute($conditionDTO->started_at, $conditionDTO->finished_at);

        $condition = new Condition();
        $condition->duration = $conditionDTO->duration;
        $condition->commission = $conditionDTO->commission;
        $condition->discount = $conditionDTO->discount;
        $condition->is_special = empty($store_ids) === false;
        $condition->special_offer = $conditionDTO->special_offer;
        $condition->event_id = $conditionDTO->event_id;
        $condition->post_merchant = $conditionDTO->post_merchant;
        $condition->post_alifshop = $conditionDTO->post_alifshop;
        $condition->merchant_id = $merchant->id;
        $condition->store_id = $main_store->id;
        $condition->started_at = $conditionDTO->started_at ? Carbon::parse($conditionDTO->started_at) : null;
        $condition->finished_at = $conditionDTO->finished_at ? Carbon::parse($conditionDTO->finished_at) : null;
        $condition->active = $conditionDTO->started_at === null;

        $condition->save();

        if ($store_ids) {
            $condition->stores()->attach($store_ids);
        }

        SendHook::dispatch(new HookData(
            service: 'merchants',
            hookable_type: $merchant->getTable(),
            hookable_id: $merchant->id,
            created_from_str: 'PRM',
            created_by_id: $this->gatewayAuthUser->getId(),
            body: 'Создано условие',
            keyword: 'id: ' . $condition->id . ' ' . $condition->title,
            action: 'create',
            class: 'info',
            action_at: null,
            created_by_str: $this->gatewayAuthUser->getName(),
        ));

        $this->flushCacheUseCase->execute($merchant->id);

        return $condition->load('stores');
    }
}
