<?php

declare(strict_types=1);

namespace App\UseCases\ApplicationConditions;

use App\Exceptions\BusinessException;
use App\HttpRepositories\Alifshop\AlifshopHttpRepository;
use App\Modules\Merchants\Models\Condition;
use App\UseCases\Cache\FlushCacheUseCase;

class TogglePostsApplicationConditionUseCase
{
    public function __construct(
        private AlifshopHttpRepository $alifshopHttpRepository,
        private FindConditionByIdUseCase $findConditionUseCase,
        private FlushCacheUseCase $flushCacheUseCase
    ) {
    }

    public function execute(int $id, bool $post_merchant, bool $post_alifshop) : Condition
    {
        $condition = $this->findConditionUseCase->execute($id);

        $merchant = $condition->merchant;

        if ($merchant->stores()->where('is_main', true)->exists() === false) {
            throw new BusinessException('У данного мерчанта нет основного магазина ' . $merchant->name, 'main_store_not_exists', 400);
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
                'event_id' => $item->event_id,
            ];
        });

        $this->alifshopHttpRepository->storeOrUpdateConditions($merchant->company_id, $conditions);
        $this->flushCacheUseCase->execute($merchant->id);

        return $condition;
    }
}
