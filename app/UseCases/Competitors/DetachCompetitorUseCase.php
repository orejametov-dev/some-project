<?php

namespace App\UseCases\Competitors;

use App\UseCases\Merchants\FindMerchantByIdUseCase;

class DetachCompetitorUseCase
{
    public function __construct(
        private FindMerchantByIdUseCase       $findMerchantUseCase,
        private FindCompetitorUseCase         $findCompetitorUseCase,
        private FindMerchantCompetitorUseCase $findMerchantCompetitorUseCase,
    ) {
    }

    public function execute(int $merchant_id, int $competitor_id): void
    {
        $merchant = $this->findMerchantUseCase->execute($merchant_id);
        $competitor = $this->findCompetitorUseCase->execute($competitor_id);

        $this->findMerchantCompetitorUseCase->execute($merchant, $competitor->id);

        $merchant->competitors()->detach($competitor->id);
    }
}
