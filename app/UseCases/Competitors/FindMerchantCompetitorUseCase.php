<?php

namespace App\UseCases\Competitors;

use App\Exceptions\BusinessException;
use App\Modules\Merchants\Models\Merchant;

class FindMerchantCompetitorUseCase
{
    public function execute(Merchant $merchant, int $merchant_competitor_id)
    {
        $merchant_competitor = $merchant->competitors()->find($merchant_competitor_id);

        if ($merchant_competitor === null) {
            throw new BusinessException('Информация по данному конкуренту не найдена', 'object_not_found', 404);
        }

        return $merchant_competitor;
    }
}
