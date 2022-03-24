<?php

declare(strict_types=1);

namespace App\UseCases\MerchantRequests;

use App\HttpRepositories\Storage\StorageHttpRepository;
use App\Models\File;
use Illuminate\Http\UploadedFile;

class UploadMerchantRequestFileUseCase
{
    public function __construct(
        private FindMerchantRequestByIdUseCase $findMerchantRequestByIdUseCase,
        private StorageHttpRepository $storageHttpRepository
    ) {
    }

    public function execute(int $id, string $file_type, UploadedFile $uploadedFile): File
    {
        $merchant_request = $this->findMerchantRequestByIdUseCase->execute($id);

        $storage_file = $this->storageHttpRepository->uploadFile($uploadedFile, 'merchants');

        $merchant_request_file = new File();
        $merchant_request_file->file_type = $file_type;
        $merchant_request_file->mime_type = $storage_file->getMimeType();
        $merchant_request_file->size = $storage_file->getSize();
        $merchant_request_file->url = $storage_file->getUrl();
        $merchant_request_file->request_id = $merchant_request->id;
        $merchant_request_file->save();

        $exist_file_type = $merchant_request->files->pluck('file_type')->toArray();
        $file_checker = true;
        unset(File::$registration_file_types['store_photo']);
        foreach (File::$registration_file_types as $key => $file_type) {
//            $file_checker = $file_checker && true;
            if (in_array($key, $exist_file_type) === false) {
                $file_checker = false;
            }
        }

        if ($file_checker === true) {
            $merchant_request->file_completed = true;
            $merchant_request->save();
        }

        return $merchant_request_file;
    }
}
