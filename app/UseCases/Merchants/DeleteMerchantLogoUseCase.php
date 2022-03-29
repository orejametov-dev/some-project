<?php

declare(strict_types=1);

namespace App\UseCases\Merchants;

use App\HttpRepositories\Storage\StorageHttpRepository;

class DeleteMerchantLogoUseCase
{
    public function __construct(
        private FindMerchantByIdUseCase $findMerchantByIdUseCase,
        private StorageHttpRepository $storageHttpRepository
    ) {
    }

    public function execute(int $id): void
    {
        $merchant = $this->findMerchantByIdUseCase->execute($id);

        if ($merchant->logo_url === null) {
            return;
        }
        $this->storageHttpRepository->destroy($merchant->logo_url);

        $merchant->logo_url = null;
        $merchant->save();
    }
}
