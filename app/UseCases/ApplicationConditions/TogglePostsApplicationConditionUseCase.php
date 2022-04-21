<?php

declare(strict_types=1);

namespace App\UseCases\ApplicationConditions;

use App\Exceptions\BusinessException;
use App\HttpRepositories\Alifshop\AlifshopHttpRepository;
use App\Models\Condition;
use App\Repositories\ApplicationConditionRepository;
use App\Repositories\StoreRepository;
use App\UseCases\Cache\FlushCacheUseCase;
use App\UseCases\Merchants\FindMerchantByIdUseCase;
use Illuminate\Support\Collection;

class TogglePostsApplicationConditionUseCase
{
    public function __construct(
        private AlifshopHttpRepository $alifshopHttpRepository,
        private FindConditionByIdUseCase $findConditionByIdUseCase,
        private FlushCacheUseCase $flushCacheUseCase,
        private ApplicationConditionRepository $applicationConditionRepository,
        private FindMerchantByIdUseCase $findMerchantByIdUseCase,
        private StoreRepository $storeRepository,
    ) {
    }

    public function execute(int $id, bool $post_merchant, bool $post_alifshop): Condition
    {
        $condition = $this->findConditionByIdUseCase->execute($id);

        $merchant = $this->findMerchantByIdUseCase->execute($condition->merchant_id);

        if ($this->storeRepository->getByIsMainTrueMerchantId($merchant->id) === null) {
            throw new BusinessException('У данного мерчанта нет основного магазина ' . $merchant->name, 'main_store_not_exists', 400);
        }

        $condition->post_merchant = $post_merchant;
        $condition->post_alifshop = $post_alifshop;
        $this->applicationConditionRepository->save($condition);

        $conditions = $this->applicationConditionRepository->getByActiveTruePostAlifshopTrueWithMerchantId($merchant->id);

        $item = [];
        foreach ($conditions as $condition_item) {
            $item[] = [
                'id' => $condition_item->id,
                'commission' => $condition_item->commission,
                'duration' => $condition_item->duration,
                'event_id' => $condition_item->event_id,
            ];
        }

        $this->alifshopHttpRepository->storeOrUpdateConditions($merchant->company_id, Collection::make($item));
        $this->flushCacheUseCase->execute($merchant->id);

        return $condition;
    }
}
