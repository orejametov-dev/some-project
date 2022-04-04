<?php

namespace App\UseCases\Merchants;

use App\UseCases\Cache\FlushCacheUseCase;

class ToggleIntegrationUseCase
{
    public function __construct(
        private FindMerchantByIdUseCase $findMerchantByIdUseCase,
        private FlushCacheUseCase $flushCacheUseCase,
    ) {
    }

    public function execute(int $id)
    {
        $merchant = $this->findMerchantByIdUseCase->execute($id);
        $merchant->integration = !$merchant->integration;
        $merchant->save();

        $this->flushCacheUseCase->execute($merchant->id);

        return $merchant;
    }
}
