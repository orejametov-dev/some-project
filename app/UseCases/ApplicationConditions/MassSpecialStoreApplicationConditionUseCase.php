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
use App\Models\Merchant;
use App\UseCases\Cache\FlushCacheUseCase;

class MassSpecialStoreApplicationConditionUseCase
{
    public function __construct(
        private AlifshopHttpRepository $alifshopHttpRepository,
        private CheckStartedAtAndFinishedAtConditionUseCase $checkStartedAtAndFinishedAtConditionUseCase,
        private FlushCacheUseCase $flushCacheUseCase,
        private GatewayAuthUser $gatewayAuthUser
    ) {
    }

    public function execute(MassSpecialStoreConditionDTO $massSpecialStoreConditionDTO): void
    {
        $merchants = Merchant::query()
            ->whereIn('id', $massSpecialStoreConditionDTO->getMerchantIds())
            ->get();

        if (array_diff($massSpecialStoreConditionDTO->getMerchantIds(), $merchants->pluck('id')->toArray()) != null) {
            throw new ApiBusinessException('Мерчант не существует', 'merchant_not_exists', [
                'ru' => 'Мерчант не существует',
            ], 400);
        }

        $this->checkStartedAtAndFinishedAtConditionUseCase->execute($massSpecialStoreConditionDTO->getStartedAt(), $massSpecialStoreConditionDTO->getFinishedAt());

        foreach ($merchants as $merchant) {
            $main_store = $merchant->stores()->where('is_main', true)->first();

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

            $condition->save();

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

            $merchant->load(['application_conditions' => function ($q) {
                $q->active();
            }]);

            $conditions = $merchant->application_conditions->where('post_alifshop', true)->map(function ($item) {
                return [
                    'id' => $item->id,
                    'commission' => $item->commission,
                    'duration' => $item->duration,
                    'event_id' => $item->event_id,
                ];
            });

            $this->alifshopHttpRepository->storeOrUpdateConditions((int) $merchant->company_id, $conditions);
            $this->flushCacheUseCase->execute($merchant->id);
        }
    }
}
