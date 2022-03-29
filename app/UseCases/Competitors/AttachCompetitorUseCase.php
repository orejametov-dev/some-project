<?php

namespace App\UseCases\Competitors;

use App\DTOs\Competitors\SaveCompetitorDTO;
use App\Exceptions\ApiBusinessException;
use App\Models\Merchant;
use App\UseCases\Merchants\FindMerchantByIdUseCase;

class AttachCompetitorUseCase
{
    public function __construct(
        private FindMerchantByIdUseCase $findMerchantUseCase,
        private FindCompetitorUseCase $findCompetitorUseCase,
    ) {
    }

    public function execute(int $merchant_id, SaveCompetitorDTO $competitorDTO): Merchant
    {
        $merchant = $this->findMerchantUseCase->execute($merchant_id);
        $competitor = $this->findCompetitorUseCase->execute($competitorDTO->getCompetitorId());

        if ($merchant->competitors()->find($competitor->id) !== null) {
            throw new ApiBusinessException('Информация о данном конкуренте на этого мерчанта уже была создана', 'merchant_competitor_exists', [
                'ru' => 'Информация о данном конкуренте на этого мерчанта уже была создана',
                'uz' => 'Merchantdagi bu konkurent haqidagi ma`lumot qo`shib bo`lingan ekan',
            ], 400);
        }

        $merchant->competitors()->attach($competitor->id, [
            'volume_sales' => $competitorDTO->getVolumeSales(),
            'percentage_approve' => $competitorDTO->getPercentageApprove(),
            'partnership_at' => $competitorDTO->getPartnershipAt(),
        ]);

        return $merchant->load('competitors');
    }
}
