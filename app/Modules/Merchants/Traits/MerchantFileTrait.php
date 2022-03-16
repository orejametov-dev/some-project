<?php

declare(strict_types=1);

namespace App\Modules\Merchants\Traits;

use App\HttpRepositories\Storage\StorageHttpRepository;
use App\Modules\Merchants\Models\File;
use Illuminate\Http\UploadedFile;

trait MerchantFileTrait
{
    public function uploadLogo(UploadedFile $uploadedAvatar): self
    {
        if ($this->logo_url) {
            (new StorageHttpRepository())->destroy($this->logo_url);
        }
        $storage_file = (new StorageHttpRepository())->uploadFile($uploadedAvatar, 'merchants');

        $this->logo_url = $storage_file->getUrl();
        $this->save();

        return $this;
    }

    public function deleteLogo(): void
    {
        if (!$this->logo_url) {
            return;
        }
        (new StorageHttpRepository())->destroy($this->logo_url);

        $this->logo_url = null;
        $this->save();
    }

    public function uploadFile(UploadedFile $uploadedFile, string $type): File
    {
        $storage_file = (new StorageHttpRepository)->uploadFile($uploadedFile, 'merchants');
        $merchant_file = new File();
        $merchant_file->file_type = $type;
        $merchant_file->mime_type = $storage_file->getMimeType();
        $merchant_file->size = $storage_file->getSize();
        $merchant_file->url = $storage_file->getUrl();
        $merchant_file->merchant_id = $this->id;
        $merchant_file->save();

        return $merchant_file;
    }

    public function deleteFile(int $file_id): void
    {
        $file = $this->files()->find($file_id);
        if (!$file) {
            return;
        }

        (new StorageHttpRepository())->destroy($file->url);
        $file->delete();
    }
}
