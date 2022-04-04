<?php

declare(strict_types=1);

namespace App\UseCases\Merchants;

use App\HttpRepositories\Storage\StorageHttpRepository;

class DeleteMerchantFileUseCase
{
    public function __construct(
        private FindMerchantByIdUseCase $findMerchantByIdUseCase,
        private StorageHttpRepository $storageHttpRepository
    ) {
    }

    public function execute(int $id, int $file_id): void
    {
        $merchant = $this->findMerchantByIdUseCase->execute($id);

        $file = $merchant->files()->find($file_id);
        if (!$file) {
            return;
        }

        $this->storageHttpRepository->destroy($file->url);
        $file->delete();
    }
}
