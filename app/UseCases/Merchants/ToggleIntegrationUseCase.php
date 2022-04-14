<?php

namespace App\UseCases\Merchants;

use App\Models\Merchant;
use App\Repositories\MerchantRepository;
use App\UseCases\Cache\FlushCacheUseCase;

class ToggleIntegrationUseCase
{
    public function __construct(
        private FindMerchantByIdUseCase $findMerchantByIdUseCase,
        private FlushCacheUseCase $flushCacheUseCase,
        private MerchantRepository $merchantRepository
    ) {
    }

    public function execute(int $id): Merchant
    {
        $merchant = $this->findMerchantByIdUseCase->execute($id);
        $merchant->integration = !$merchant->integration;
        $this->merchantRepository->save($merchant);

        $this->flushCacheUseCase->execute($merchant->id);

        return $merchant;
    }
}
