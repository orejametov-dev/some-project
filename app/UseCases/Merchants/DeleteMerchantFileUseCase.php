<?php

declare(strict_types=1);

namespace App\UseCases\Merchants;

use App\HttpRepositories\Storage\StorageHttpRepository;
use App\Repositories\FileRepository;

class DeleteMerchantFileUseCase
{
    public function __construct(
        private FindMerchantByIdUseCase $findMerchantByIdUseCase,
        private StorageHttpRepository $storageHttpRepository,
        private FileRepository $fileRepository,
    ) {
    }

    public function execute(int $id, int $file_id): void
    {
        $merchant = $this->findMerchantByIdUseCase->execute($id);

        $file = $this->fileRepository->getByIdWithMerchantId($merchant->id, $file_id);
        if (!$file) {
            return;
        }

        $this->storageHttpRepository->destroy($file->url);
        $this->fileRepository->delete($file);
    }
}
