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
use App\Repositories\ApplicationConditionRepository;
use App\Repositories\ConditionTemplateRepository;
use App\Repositories\MerchantRepository;
use App\Repositories\StoreRepository;
use App\UseCases\Cache\FlushCacheUseCase;
use Illuminate\Support\Collection;

class MassStoreApplicationConditionUseCase
{
    public function __construct(
        private AlifshopHttpRepository $alifshopHttpRepository,
        private CheckStartedAtAndFinishedAtConditionUseCase $checkStartedAtAndFinishedAtConditionUseCase,
        private FlushCacheUseCase $flushCacheUseCase,
        private GatewayAuthUser $gatewayAuthUser,
        private MerchantRepository $merchantRepository,
        private ConditionTemplateRepository $conditionTemplateRepository,
        private ApplicationConditionRepository $applicationConditionRepository,
        private StoreRepository $storeRepository,
    ) {
    }

    public function execute(MassStoreConditionDTO $massStoreConditionDTO) : void
    {
        $merchants = $this->merchantRepository->getByIds($massStoreConditionDTO->getMerchantIds());

        if (array_diff($massStoreConditionDTO->getMerchantIds(), $merchants->pluck('id')->toArray()) != null) {
            throw new ApiBusinessException('Мерчант не существует', 'merchant_not_exists', [
                'ru' => 'Мерчант не существует',
            ], 400);
        }

        $templates = $this->conditionTemplateRepository->getByIds($massStoreConditionDTO->getTemplateIds());

        if (array_diff($massStoreConditionDTO->getTemplateIds(), $templates->pluck('id')->toArray()) != null) {
            throw new ApiBusinessException('Условие не существует', 'merchant_not_exists', [
                'ru' => 'Условие не существует',
            ], 400);
        }

        $this->checkStartedAtAndFinishedAtConditionUseCase->execute($massStoreConditionDTO->getStartedAt(), $massStoreConditionDTO->getFinishedAt());

        foreach ($merchants as $merchant) {
            foreach ($templates as $template) {
                $condition = $this->applicationConditionRepository
                    ->getByDurationAndCommissionWithMerchantIdExists($merchant->id, $template->duration, $template->commission);

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
            $main_store = $this->storeRepository->getByIsMainTrueMerchantId($merchant->id);

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
            }

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
