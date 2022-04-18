<?php

declare(strict_types=1);

namespace App\UseCases\ApplicationConditions;

use Alifuz\Utils\Gateway\Entities\Auth\GatewayAuthUser;
use App\DTOs\Conditions\MassSpecialStoreConditionDTO;
use App\Exceptions\ApiBusinessException;
use App\Exceptions\BusinessException;
use App\HttpRepositories\Alifshop\AlifshopHttpRepository;
use App\HttpRepositories\Hooks\DTO\HookData;
use App\Jobs\SendHook;
use App\Models\Condition;
use App\Repositories\ApplicationConditionRepository;
use App\Repositories\MerchantRepository;
use App\Repositories\StoreRepository;
use App\UseCases\Cache\FlushCacheUseCase;
use Illuminate\Support\Collection;

class MassSpecialStoreApplicationConditionUseCase
{
    public function __construct(
        private AlifshopHttpRepository $alifshopHttpRepository,
        private CheckStartedAtAndFinishedAtConditionUseCase $checkStartedAtAndFinishedAtConditionUseCase,
        private FlushCacheUseCase $flushCacheUseCase,
        private GatewayAuthUser $gatewayAuthUser,
        private ApplicationConditionRepository $applicationConditionRepository,
        private MerchantRepository $merchantRepository,
        private StoreRepository $storeRepository,
    ) {
    }

    public function execute(MassSpecialStoreConditionDTO $massSpecialStoreConditionDTO): void
    {
        $merchants = $this->merchantRepository->getByIds($massSpecialStoreConditionDTO->getMerchantIds());

        if (array_diff($massSpecialStoreConditionDTO->getMerchantIds(), $merchants->pluck('id')->toArray()) != null) {
            throw new ApiBusinessException('Мерчант не существует', 'merchant_not_exists', [
                'ru' => 'Мерчант не существует',
            ], 400);
        }

        $this->checkStartedAtAndFinishedAtConditionUseCase->execute($massSpecialStoreConditionDTO->getStartedAt(), $massSpecialStoreConditionDTO->getFinishedAt());

        foreach ($merchants as $merchant) {
            $main_store = $this->storeRepository->getByIdWithMerchantIdIsMain($merchant->id);

            if ($main_store === null) {
                throw new BusinessException('У данного мерчанта нет основного магазина ' . $merchant->name, 'main_store_not_exists', 400);
            }

            $condition = new Condition();
            $condition->duration = $massSpecialStoreConditionDTO->getDuration();
            $condition->commission = $massSpecialStoreConditionDTO->getCommission();
            $condition->discount = $massSpecialStoreConditionDTO->getDiscount();
            $condition->post_merchant = $massSpecialStoreConditionDTO->isPostMerchant();
            $condition->post_alifshop = $massSpecialStoreConditionDTO->isPostAlifshop();
            $condition->event_id = $massSpecialStoreConditionDTO->getEventId();
            $condition->merchant_id = $merchant->id;
            $condition->store_id = $main_store->id;
            $condition->started_at = $massSpecialStoreConditionDTO->getStartedAt();
            $condition->finished_at = $massSpecialStoreConditionDTO->getFinishedAt();
            $condition->active = $massSpecialStoreConditionDTO->getStartedAt() === null;

            $this->applicationConditionRepository->save($condition);

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

            $conditions = $this->applicationConditionRepository->getByActiveTruePostAlifshopTrueWithMerchantId($merchant->id);

            $item = [];
            foreach ($conditions as $condition) {
                $item[] = [
                    'id' => $condition->id,
                    'commission' => $condition->commission,
                    'duration' => $condition->duration,
                    'event_id' => $condition->event_id,
                ];
            }

            $this->alifshopHttpRepository->storeOrUpdateConditions($merchant->company_id, Collection::make($item));
            $this->flushCacheUseCase->execute($merchant->id);
        }
    }
}
