<?php

namespace App\UseCases\Competitors;

use App\DTOs\Competitors\CompetitorDTO;
use App\Modules\Merchants\Models\Merchant;
use App\UseCases\Merchants\FindMerchantUseCase;

class UpdateCompetitorUseCase
{
    public function __construct(
        private FindMerchantUseCase $findMerchantUseCase,
        private FindCompetitorUseCase $findCompetitorUseCase,
        private FindMerchantCompetitorUseCase $findMerchantCompetitorUseCase,
    ) {
    }

    public function execute(CompetitorDTO $competitorDTO): Merchant
    {
        $merchant = $this->findMerchantUseCase->execute($competitorDTO->merchant_id);
        $competitor = $this->findCompetitorUseCase->execute($competitorDTO->competitor_id);

        $this->findMerchantCompetitorUseCase->execute($merchant, $competitor->id);

        $merchant->competitors()->detach($competitor->id);
        $merchant->competitors()->attach($competitor->id, [
            'volume_sales' => $competitorDTO->volume_sales,
            'percentage_approve' => (int) $competitorDTO->percentage_approve,
            'partnership_at' => $competitorDTO->partnership_at,
        ]);

        return $merchant->load('competitors');
    }
}
