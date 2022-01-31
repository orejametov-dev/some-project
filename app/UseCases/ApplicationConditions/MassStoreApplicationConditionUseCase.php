<?php

namespace App\UseCases\ApplicationConditions;

use App\DTOs\Conditions\MassStoreConditionDTO;
use App\Exceptions\ApiBusinessException;
use App\Exceptions\BusinessException;
use App\HttpRepositories\Alifshop\AlifshopHttpRepository;
use App\HttpServices\Hooks\DTO\HookData;
use App\Jobs\SendHook;
use App\Modules\Merchants\Models\Condition;
use App\Modules\Merchants\Models\ConditionTemplate;
use App\Modules\Merchants\Models\Merchant;
use App\UseCases\Cache\FlushCacheUseCase;
use Carbon\Carbon;

class MassStoreApplicationConditionUseCase
{
    public function __construct(
        private AlifshopHttpRepository                      $alifshopHttpRepository,
        private CheckStartedAtAndFinishedAtConditionUseCase $checkStartedAtAndFinishedAtConditionUseCase,
        private FlushCacheUseCase                           $flushCacheUseCase
    )
    {
    }

    public function execute(MassStoreConditionDTO $massStoreConditionDTO)
    {
        $merchants = Merchant::query()
            ->whereIn('id', $massStoreConditionDTO->merchant_ids)
            ->get();

        if (array_diff($massStoreConditionDTO->merchant_ids, $merchants->pluck('id')->toArray()) != null) {
            throw new ApiBusinessException('Мерчант не существует', 'merchant_not_exists', [
                'ru' => 'Мерчант не существует'
            ], 400);
        }

        $templates = ConditionTemplate::query()
            ->whereIn('id', $massStoreConditionDTO->template_ids)
            ->get();

        if (array_diff($massStoreConditionDTO->template_ids, $templates->pluck('id')->toArray()) != null) {
            throw new ApiBusinessException('Условие не существует', 'merchant_not_exists', [
                'ru' => 'Условие не существует'
            ], 400);
        }

        $this->checkStartedAtAndFinishedAtConditionUseCase->execute($massStoreConditionDTO->started_at, $massStoreConditionDTO->finished_at);

        foreach ($merchants as $merchant) {
            foreach ($templates as $template) {
                $condition = Condition::query()->where('merchant_id', $merchant->id)
                    ->where('duration', $template->duration)
                    ->where('commission', $template->commission)
                    ->exists();

                if ($condition) {
                    throw new BusinessException('Данное условие существует для этого мерчанта '
                        . $merchant->name . ' ' . $template->duration . '|' . $template->commission . '%',
                        'condition_exists', 400);
                }
            }
        }

        foreach ($merchants as $merchant) {
            $main_store = $merchant->stores()->where('is_main', true)->first();

            if ($main_store === false) {
                throw new BusinessException('У данного мерчанта нет основного магазина ' . $merchant->name, 'main_store_not_exists', 400);
            }
            foreach ($templates as $template) {

                $condition = new Condition();
                $condition->duration = $template->duration;
                $condition->commission = $template->commission;
                $condition->post_merchant = $massStoreConditionDTO->post_merchant;
                $condition->post_alifshop = $massStoreConditionDTO->post_alifshop;
                $condition->event_id = $massStoreConditionDTO->event_id;
                $condition->merchant_id = $merchant->id;
                $condition->store_id = $main_store->id;
                $condition->started_at = $massStoreConditionDTO->started_at ? Carbon::parse($massStoreConditionDTO->started_at)->format('Y-m-d') : null;
                $condition->finished_at = $massStoreConditionDTO->finished_at ? Carbon::parse($massStoreConditionDTO->finished_at)->format('Y-m-d') : null;
                $condition->active = $massStoreConditionDTO->started_at === null;

                $condition->save();

                SendHook::dispatch(new HookData(
                    service: 'merchants',
                    hookable_type: $merchant->getTable(),
                    hookable_id: $merchant->id,
                    created_from_str: 'PRM',
                    created_by_id: $massStoreConditionDTO->user_id,
                    body: 'Создано условие',
                    keyword: 'id: ' . $condition->id . ' ' . $condition->title,
                    action: 'create',
                    class: 'info',
                    action_at: null,
                    created_by_str: $massStoreConditionDTO->user_name,
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
                    'event_id' => $item->event_id
                ];
            });


            $this->alifshopHttpRepository->storeOrUpdateConditions($merchant->company_id, $conditions);
            $this->flushCacheUseCase->execute($merchant->id);
        }

        return response()->json(['message' => 'Условия изменены']);
    }
}
