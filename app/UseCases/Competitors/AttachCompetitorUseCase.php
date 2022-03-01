<?php

namespace App\UseCases\Competitors;

use App\DTOs\Competitors\CompetitorDTO;
use App\Exceptions\ApiBusinessException;
use App\Modules\Merchants\Models\Merchant;
use App\UseCases\Merchants\FindMerchantUseCase;

class AttachCompetitorUseCase
{
    public function __construct(
        private FindMerchantUseCase $findMerchantUseCase,
        private FindCompetitorUseCase $findCompetitorUseCase,
    ) {
    }

    public function execute(CompetitorDTO $competitorDTO): Merchant
    {
        $merchant = $this->findMerchantUseCase->execute($competitorDTO->merchant_id);
        $competitor = $this->findCompetitorUseCase->execute($competitorDTO->competitor_id);

        if ($merchant->competitors()->find($competitor->id) !== null) {
            throw new ApiBusinessException('Информация о данном конкуренте на этого мерчанта уже была создана', 'merchant_competitor_exists', [
                'ru' => 'Информация о данном конкуренте на этого мерчанта уже была создана',
                'uz' => 'Merchantdagi bu konkurent haqidagi ma\'lumot qo\'shib bo\'lingan ekan',
            ], 400);
        }

        $merchant->competitors()->attach($competitor->id, [
            'volume_sales' => $competitorDTO->volume_sales,
            'percentage_approve' => $competitorDTO->percentage_approve,
            'partnership_at' => $competitorDTO->partnership_at,
        ]);

        return $merchant->load('competitors');
    }
}
