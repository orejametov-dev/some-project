<?php

declare(strict_types=1);

namespace App\UseCases\ApplicationConditions;

use Alifuz\Utils\Gateway\Entities\Auth\GatewayAuthUser;
use App\DTOs\Conditions\UpdateConditionDTO;
use App\Exceptions\BusinessException;
use App\HttpRepositories\Core\CoreHttpRepository;
use App\HttpRepositories\Hooks\DTO\HookData;
use App\Jobs\SendHook;
use App\Models\Condition;
use App\Models\SpecialStoreCondition;
use App\Repositories\ApplicationConditionRepository;
use App\Repositories\SpecialStoreConditionRepository;
use App\Repositories\StoreRepository;
use App\UseCases\Cache\FlushCacheUseCase;
use App\UseCases\Merchants\FindMerchantByIdUseCase;

class UpdateApplicationConditionUseCase
{
    public function __construct(
        private CoreHttpRepository $coreHttpRepository,
        private FindConditionByIdUseCase $findConditionByIdUseCase,
        private FlushCacheUseCase $flushCacheUseCase,
        private GatewayAuthUser $gatewayAuthUser,
        private StoreRepository $storeRepository,
        private SpecialStoreConditionRepository $specialStoreConditionRepository,
        private ApplicationConditionRepository $applicationConditionRepository,
        private FindMerchantByIdUseCase $findMerchantByIdUseCase,
    ) {
    }

    public function execute(int $id, UpdateConditionDTO $updateConditionDTO) : Condition
    {
        $condition = $this->findConditionByIdUseCase->execute($id);

        if ($this->coreHttpRepository->checkApplicationToExistByConditionId($id)
            && $condition->commission !== $updateConditionDTO->getCommission()
            && $condition->duration !== $updateConditionDTO->getDuration()
            && $condition->discount !== $updateConditionDTO->getDiscount()
        ) {
            throw new BusinessException('Условие не может быть изменено', 'not_allowed', 400);
        }

        $merchant = $this->findMerchantByIdUseCase->execute($condition->merchant_id);

        $store_ids = $updateConditionDTO->getStoreIds();

        $merchant_stores = $this->storeRepository->getByActiveTrueMerchantId($merchant->id);

        //dd($merchant_stores->whereIn('id', $store_ids)->pluck('id')->toArray(), $store_ids);
        if (array_diff($store_ids, $merchant_stores->whereIn('id', $store_ids)->pluck('id')->toArray()) != null) {
            throw new BusinessException('Указан не правильно магазин', 'wrong_store', 400);
        }

        $main_store = $this->storeRepository->getByIsMainTrueMerchantId($merchant->id);

        if ($main_store === null) {
            throw new BusinessException('У данного мерчанта нет основного магазина ' . $merchant->name, 'main_store_not_exists', 400);
        }

        if (in_array($main_store->id, $store_ids) === false) {
            $store_ids[] = $main_store->id;
        }

        // TODO fix need replace attach and detach
        $special_store_conditions = $this->specialStoreConditionRepository->getByConditionId($condition->id);
        foreach ($special_store_conditions as $special_store_condition) {
            $this->specialStoreConditionRepository->delete($special_store_condition);
        }

        foreach ($store_ids as $store_id) {
            $special_store_condition = new SpecialStoreCondition();
            $special_store_condition->store_id = $store_id;
            $special_store_condition->condition_id = $condition->id;
            $this->specialStoreConditionRepository->save($special_store_condition);
        }

        $condition->duration = $updateConditionDTO->getDuration();
        $condition->commission = $updateConditionDTO->getCommission();
        $condition->discount = $updateConditionDTO->getDiscount();
        $condition->is_special = empty($store_ids) === false;
        $condition->special_offer = $updateConditionDTO->getSpecialOffer();
        $condition->event_id = $updateConditionDTO->getEventId();

        $this->applicationConditionRepository->save($condition);

        SendHook::dispatch(new HookData(
            service: 'merchants',
            hookable_type: $merchant->getTable(),
            hookable_id: $merchant->id,
            created_from_str: 'PRM',
            created_by_id: $this->gatewayAuthUser->getId(),
            body: 'Изменено условие',
            keyword: 'id: ' . $condition->id . ' ' . $condition->title,
            action: 'update',
            class: 'warning',
            action_at: null,
            created_by_str: $this->gatewayAuthUser->getName(),
        ));

        $this->flushCacheUseCase->execute($merchant->id);

        return $condition;
    }
}
