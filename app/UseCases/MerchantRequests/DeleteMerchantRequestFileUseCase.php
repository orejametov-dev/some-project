<?php

declare(strict_types=1);

namespace App\UseCases\MerchantRequests;

use App\HttpRepositories\Storage\StorageHttpRepository;

class DeleteMerchantRequestFileUseCase
{
    public function __construct(
        private FindMerchantRequestByIdUseCase $findMerchantRequestByIdUseCase,
        private StorageHttpRepository $storageHttpRepository
    ) {
    }

    public function execute(int $id, int $file_id): void
    {
        $merchant_request = $this->findMerchantRequestByIdUseCase->execute($id);

        $file = $merchant_request->files()->find($file_id);
        if (!$file) {
            return;
        }

        $this->storageHttpRepository->destroy($file->url);
        $file->delete();
    }
}
