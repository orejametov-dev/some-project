<?php

declare(strict_types=1);

namespace App\UseCases\ApplicationConditions;

use Alifuz\Utils\Gateway\Entities\Auth\GatewayAuthUser;
use App\DTOs\Conditions\StoreConditionDTO;
use App\Exceptions\BusinessException;
use App\HttpRepositories\Hooks\DTO\HookData;
use App\Jobs\SendHook;
use App\Models\Condition;
use App\Models\SpecialStoreCondition;
use App\Repositories\ApplicationConditionRepository;
use App\Repositories\SpecialStoreConditionRepository;
use App\Repositories\StoreRepository;
use App\UseCases\Cache\FlushCacheUseCase;
use App\UseCases\Merchants\FindMerchantByIdUseCase;

class StoreApplicationConditionUseCase
{
    public function __construct(
        private CheckStartedAtAndFinishedAtConditionUseCase $checkStartedAtAndFinishedAtConditionUseCase,
        private FindMerchantByIdUseCase $findMerchantUseCase,
        private FlushCacheUseCase $flushCacheUseCase,
        private GatewayAuthUser $gatewayAuthUser,
        private StoreRepository $storeRepository,
        private ApplicationConditionRepository $applicationConditionRepository,
        private SpecialStoreConditionRepository $specialStoreConditionRepository,
    ) {
    }

    public function execute(StoreConditionDTO $conditionDTO): Condition
    {
        $merchant = $this->findMerchantUseCase->execute($conditionDTO->getMerchantId());

        $store_ids = $conditionDTO->getStoreIds();

        $merchant_stores = $this->storeRepository->getByActiveTrueMerchantId($merchant->id);

        $main_store = $this->storeRepository->getByIsMainTrueMerchantId($merchant->id);

        if (array_diff($store_ids, $merchant_stores->whereIn('id', $store_ids)->pluck('id')->toArray()) != null) {
            throw new BusinessException('Указан не правильно магазин', 'wrong_store', 400);
        }

        if ($main_store === null) {
            throw new BusinessException('У данного мерчанта нет основного магазина ' . $merchant->name, 'main_store_not_exists', 400);
        }

        if ($conditionDTO->isPostAlifshop() === true and in_array($main_store->id, $store_ids) === false) {
            $store_ids[] = $main_store->id;
        }

        $this->checkStartedAtAndFinishedAtConditionUseCase->execute($conditionDTO->getStartedAt(), $conditionDTO->getFinishedAt());

        $condition = new Condition();
        $condition->duration = $conditionDTO->getDuration();
        $condition->commission = $conditionDTO->getCommission();
        $condition->discount = $conditionDTO->getDiscount();
        $condition->is_special = empty($store_ids) === false;
        $condition->special_offer = $conditionDTO->getSpecialOffer();
        $condition->event_id = $conditionDTO->getEventId();
        $condition->post_merchant = $conditionDTO->isPostMerchant();
        $condition->post_alifshop = $conditionDTO->isPostAlifshop();
        $condition->merchant_id = $merchant->id;
        $condition->store_id = $main_store->id;
        $condition->started_at = $conditionDTO->getStartedAt();
        $condition->finished_at = $conditionDTO->getFinishedAt();
        $condition->active = $conditionDTO->getStartedAt() === null;

        $this->applicationConditionRepository->save($condition);

        if ($store_ids) {
            foreach ($store_ids as $store_id) {
                $special_store_condition = new SpecialStoreCondition();
                $special_store_condition->store_id = $store_id;
                $special_store_condition->condition_id = $condition->id;
                $this->specialStoreConditionRepository->save($special_store_condition);
            }
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

        return $condition;
    }
}
