<?php

namespace App\UseCases\ApplicationConditions;

use App\Exceptions\ApiBusinessException;
use App\Exceptions\BusinessException;
use App\HttpRepositories\Alifshop\AlifshopHttpRepository;
use App\HttpServices\Hooks\DTO\HookData;
use App\Jobs\SendHook;
use App\Modules\Merchants\DTO\Conditions\MassSpecialStoreConditionDTO;
use App\Modules\Merchants\Models\Condition;
use App\Modules\Merchants\Models\Merchant;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class MassSpecialStoreApplicationConditionUseCase
{
    public function __construct(
        private AlifshopHttpRepository $alifshopHttpRepository,
        private CheckStartedAtAndFinishedAtConditionUseCase $checkStartedAtAndFinishedAtConditionUseCase
    )
    {
    }

    public function execute(MassSpecialStoreConditionDTO $massSpecialStoreConditionDTO)
    {
        $merchants = Merchant::query()
            ->whereIn('id', $massSpecialStoreConditionDTO->merchant_ids)
            ->get();

        if (!empty(array_diff($massSpecialStoreConditionDTO->merchant_ids, $merchants->pluck('id')->toArray()))) {
            throw new ApiBusinessException('Мерчант не существует', 'merchant_not_exists', [
                'ru' => 'Мерчант не существует'
            ], 400);
        }

        foreach ($merchants as $merchant) {
            $main_store = $merchant->stores()->where('is_main', true)->first();

            if (!$main_store) {
                throw new BusinessException('У данного мерчанта нет основного магазина ' . $merchant->name, 'main_store_not_exists', 400);
            }

            $condition = new Condition();
            $condition->duration = $massSpecialStoreConditionDTO->duration;
            $condition->commission = $massSpecialStoreConditionDTO->commission;
            $condition->discount = $massSpecialStoreConditionDTO->discount;
            $condition->post_merchant = $massSpecialStoreConditionDTO->post_merchant;
            $condition->post_alifshop = $massSpecialStoreConditionDTO->post_alifshop;
            $condition->event_id = $massSpecialStoreConditionDTO->event_id;
            $condition->merchant()->associate($merchant);
            $condition->store_id = $main_store->id;

            $this->checkStartedAtAndFinishedAtConditionUseCase->execute($massSpecialStoreConditionDTO->started_at, $massSpecialStoreConditionDTO->finished_at);

            $condition->started_at = $massSpecialStoreConditionDTO->started_at ? Carbon::parse($massSpecialStoreConditionDTO->started_at)->format('Y-m-d') : null;
            $condition->finished_at = $massSpecialStoreConditionDTO->finished_at ? Carbon::parse($massSpecialStoreConditionDTO->finished_at)->format('Y-m-d') : null;

            if ($massSpecialStoreConditionDTO->started_at === null) {
                $condition->active = true;
            }

            $condition->save();

            SendHook::dispatch(new HookData(
                service: 'merchants',
                hookable_type: $merchant->getTable(),
                hookable_id: $merchant->id,
                created_from_str: 'PRM',
                created_by_id: $massSpecialStoreConditionDTO->user_id,
                body: 'Создано условие',
                keyword: 'id: ' . $condition->id . ' ' . $condition->title,
                action: 'create',
                class: 'info',
                action_at: null,
                created_by_str: $massSpecialStoreConditionDTO->user_name,
            ));

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

            Cache::tags($merchant->id)->flush();
        }

        return response()->json(['message' => 'Условия изменены']);
    }
}
