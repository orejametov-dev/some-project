<?php

declare(strict_types=1);

namespace App\UseCases\Merchants;

use App\Models\Merchant;
use Illuminate\Support\Facades\Cache;

class ToggleMerchantRecommendUseCase
{
    public function __construct(
        private FindMerchantByIdUseCase $findMerchantByIdUseCase,
    ) {
    }

    public function execute(int $id): Merchant
    {
        $merchant = $this->findMerchantByIdUseCase->execute($id);
        $merchant->recommend = !$merchant->recommend;
        $merchant->save();

        Cache::tags($merchant->id)->flush();
        Cache::tags('azo_merchants')->flush();

        return $merchant;
    }
}
