<?php

declare(strict_types=1);

namespace App\UseCases\ApplicationConditions;

use Alifuz\Utils\Gateway\Entities\Auth\GatewayAuthUser;
use App\DTOs\Conditions\MassStoreConditionDTO;
use App\Exceptions\ApiBusinessException;
use App\Exceptions\BusinessException;
use App\HttpRepositories\Alifshop\AlifshopHttpRepository;
use App\HttpRepositories\Hooks\DTO\HookData;
use App\Jobs\SendHook;
use App\Models\Condition;
use App\Models\ConditionTemplate;
use App\Models\Merchant;
use App\UseCases\Cache\FlushCacheUseCase;

class MassStoreApplicationConditionUseCase
{
    public function __construct(
        private AlifshopHttpRepository $alifshopHttpRepository,
        private CheckStartedAtAndFinishedAtConditionUseCase $checkStartedAtAndFinishedAtConditionUseCase,
        private FlushCacheUseCase $flushCacheUseCase,
        private GatewayAuthUser $gatewayAuthUser
    ) {
    }

    public function execute(MassStoreConditionDTO $massStoreConditionDTO) : void
    {
        $merchants = Merchant::query()
            ->whereIn('id', $massStoreConditionDTO->getMerchantIds())
            ->get();

        if (array_diff($massStoreConditionDTO->getMerchantIds(), $merchants->pluck('id')->toArray()) != null) {
            throw new ApiBusinessException('Мерчант не существует', 'merchant_not_exists', [
                'ru' => 'Мерчант не существует',
            ], 400);
        }

        $templates = ConditionTemplate::query()
            ->whereIn('id', $massStoreConditionDTO->getTemplateIds())
            ->get();

        if (array_diff($massStoreConditionDTO->getTemplateIds(), $templates->pluck('id')->toArray()) != null) {
            throw new ApiBusinessException('Условие не существует', 'merchant_not_exists', [
                'ru' => 'Условие не существует',
            ], 400);
        }

        $this->checkStartedAtAndFinishedAtConditionUseCase->execute($massStoreConditionDTO->getStartedAt(), $massStoreConditionDTO->getFinishedAt());

        foreach ($merchants as $merchant) {
            foreach ($templates as $template) {
                $condition = Condition::query()->where('merchant_id', $merchant->id)
                    ->where('duration', $template->duration)
                    ->where('commission', $template->commission)
                    ->exists();

                if ($condition) {
                    throw new BusinessException(
                        'Данное условие существует для этого мерчанта '
                        . $merchant->name . ' ' . $template->duration . '|' . $template->commission . '%',
                        'condition_exists',
                        400
                    );
                }
            }
        }

        foreach ($merchants as $merchant) {
            $main_store = $merchant->stores()->where('is_main', true)->first();

            if ($main_store === null) {
                throw new BusinessException('У данного мерчанта нет основного магазина ' . $merchant->name, 'main_store_not_exists', 400);
            }
            foreach ($templates as $template) {
                $condition = new Condition();
                $condition->duration = $template->duration;
                $condition->commission = $template->commission;
                $condition->post_merchant = $massStoreConditionDTO->isPostMerchant();
                $condition->post_alifshop = $massStoreConditionDTO->isPostAlifshop();
                $condition->event_id = $massStoreConditionDTO->getEventId();
                $condition->merchant_id = $merchant->id;
                $condition->store_id = $main_store->id;
                $condition->started_at = $massStoreConditionDTO->getStartedAt();
                $condition->finished_at = $massStoreConditionDTO->getFinishedAt();
                $condition->active = $massStoreConditionDTO->getStartedAt() === null;

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
            }

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
