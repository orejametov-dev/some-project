<?php

namespace App\UseCases\Competitors;

use App\DTOs\Competitors\SaveCompetitorDTO;
use App\Models\Merchant;
use App\UseCases\Merchants\FindMerchantByIdUseCase;

class UpdateCompetitorUseCase
{
    public function __construct(
        private FindMerchantByIdUseCase $findMerchantUseCase,
        private FindCompetitorUseCase $findCompetitorUseCase,
        private FindMerchantCompetitorUseCase $findMerchantCompetitorUseCase,
    ) {
    }

    public function execute(int $id, SaveCompetitorDTO $competitorDTO): Merchant
    {
        $merchant = $this->findMerchantUseCase->execute($id);
        $competitor = $this->findCompetitorUseCase->execute($competitorDTO->getCompetitorId());

        $this->findMerchantCompetitorUseCase->execute($merchant, $competitor->id);

        $merchant->competitors()->detach($competitor->id);
        $merchant->competitors()->attach($competitor->id, [
            'volume_sales' => $competitorDTO->getVolumeSales(),
            'percentage_approve' => (int) $competitorDTO->getPercentageApprove(),
            'partnership_at' => $competitorDTO->getPartnershipAt(),
        ]);

        return $merchant->load('competitors');
    }
}
