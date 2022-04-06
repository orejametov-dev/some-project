<?php

namespace App\UseCases\Competitors;

use App\Exceptions\NotFoundException;
use App\Models\Competitor;
use App\Models\Merchant;

class FindMerchantCompetitorUseCase
{
    public function execute(Merchant $merchant, int $merchant_competitor_id): Competitor
    {
        $merchant_competitor = $merchant->competitors()->find($merchant_competitor_id);

        if ($merchant_competitor === null) {
            throw new NotFoundException('Информация по данному конкуренту не найдена');
        }

        return $merchant_competitor;
    }
}
