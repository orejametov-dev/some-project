<?php

declare(strict_types=1);

namespace App\UseCases\Merchants;

use App\HttpRepositories\Storage\StorageHttpRepository;
use App\Models\File;
use Illuminate\Http\UploadedFile;

class UploadMerchantFileUseCase
{
    public function __construct(
        private FindMerchantByIdUseCase $findMerchantByIdUseCase,
        private StorageHttpRepository $storageHttpRepository
    ) {
    }

    public function execute(int $id, string $file_type, UploadedFile $uploadedFile): File
    {
        $merchant = $this->findMerchantByIdUseCase->execute($id);

        $storage_file = $this->storageHttpRepository->uploadFile($uploadedFile, 'merchants');
        $merchant_file = new File();
        $merchant_file->file_type = $file_type;
        $merchant_file->mime_type = $storage_file->getMimeType();
        $merchant_file->size = $storage_file->getSize();
        $merchant_file->url = $storage_file->getUrl();
        $merchant_file->merchant_id = $merchant->id;
        $merchant_file->save();

        return $merchant_file;
    }
}
