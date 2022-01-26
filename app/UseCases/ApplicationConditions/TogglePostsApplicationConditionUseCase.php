<?php

namespace App\UseCases\ApplicationConditions;

use App\Exceptions\BusinessException;
use App\HttpRepositories\Alifshop\AlifshopHttpRepository;
use App\Modules\Merchants\Models\Condition;
use Illuminate\Support\Facades\Cache;

class TogglePostsApplicationConditionUseCase
{
    public function __construct(
        private AlifshopHttpRepository $alifshopHttpRepository
    )
    {
    }

    public function execute(int $id, bool $post_merchant, bool $post_alifshop)
    {
        $condition = Condition::query()->find($id);

        if ($condition === null)
        {
            throw new BusinessException('Условие не найдено' , 'condition_not_found' , 404);
        }

        $merchant = $condition->merchant;
        $main_store = $merchant->stores()->main()->exists();

        if ($post_alifshop and !$main_store) {
            return response()->json(['message' => 'Для онлайн заявок надо указать основной магазин'], 400);
        }

        $condition->post_merchant = $post_merchant;
        $condition->post_alifshop = $post_alifshop;
        $condition->save();

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

        return $condition;
    }
}
