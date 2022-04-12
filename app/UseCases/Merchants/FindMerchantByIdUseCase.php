<?php

namespace App\UseCases\Merchants;

use App\Exceptions\NotFoundException;
use App\Models\Merchant;
use App\Repositories\MerchantRepository;

class FindMerchantByIdUseCase
{
    public function __construct(
        private MerchantRepository $merchantRepository
    ) {
    }

    public function execute(int $merchant_id): Merchant
    {
        $merchant = $this->merchantRepository->findById($merchant_id);
        if ($merchant === null) {
            throw new NotFoundException('Мерчант не найден');
        }

        return $merchant;
    }
}
