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
use App\Models\Store;
use App\UseCases\Cache\FlushCacheUseCase;

class UpdateApplicationConditionUseCase
{
    public function __construct(
        private CoreHttpRepository $coreHttpRepository,
        private FindConditionByIdUseCase $findConditionUseCase,
        private FlushCacheUseCase $flushCacheUseCase,
        private GatewayAuthUser $gatewayAuthUser
    ) {
    }

    public function execute(int $id, UpdateConditionDTO $updateConditionDTO) : Condition
    {
        $condition = $this->findConditionUseCase->execute($id);

        if ($this->coreHttpRepository->checkApplicationToExistByConditionId($id)
            && $condition->commission !== $updateConditionDTO->getCommission()
            && $condition->duration !== $updateConditionDTO->getDuration()
            && $condition->discount !== $updateConditionDTO->getDiscount()
        ) {
            throw new BusinessException('Условие не может быть изменено', 'not_allowed', 400);
        }

        $merchant = $condition->merchant;

        $store_ids = $updateConditionDTO->getStoreIds();

        $merchant_stores = Store::query()
            ->where('merchant_id', $merchant->id)
            ->where('active', true)
            ->get();

        if (array_diff($store_ids, $merchant_stores->whereIn('id', $store_ids)->pluck('id')->toArray()) != null) {
            throw new BusinessException('Указан не правильно магазин', 'wrong_store', 400);
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

        $condition->duration = $updateConditionDTO->getDuration();
        $condition->commission = $updateConditionDTO->getCommission();
        $condition->discount = $updateConditionDTO->getDiscount();
        $condition->is_special = empty($store_ids) === false;
        $condition->special_offer = $updateConditionDTO->getSpecialOffer();
        $condition->event_id = $updateConditionDTO->getEventId();

        $condition->save();

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
