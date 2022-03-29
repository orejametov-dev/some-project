<?php

namespace App\UseCases\Merchants;

use App\UseCases\Cache\FlushCacheUseCase;

class ToggleHoldingInitialPaymentUseCase
{
    public function __construct(
        private FindMerchantByIdUseCase $findMerchantByIdUseCase,
        private FlushCacheUseCase $flushCacheUseCase,
    ) {
    }

    public function execute(int $id)
    {
        $merchant = $this->findMerchantByIdUseCase->execute($id);
        $merchant->holding_initial_payment = !$merchant->holding_initial_payment;
        $merchant->save();

        $this->flushCacheUseCase->execute($merchant->id);

        return $merchant;
    }
}
