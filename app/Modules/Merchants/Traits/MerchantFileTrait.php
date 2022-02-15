<?php

namespace App\Modules\Merchants\Traits;

use App\HttpServices\Storage\StorageMicroService;
use App\Modules\Merchants\Models\File;
use Illuminate\Http\UploadedFile;

trait MerchantFileTrait
{
    public function uploadLogo(UploadedFile $uploadedAvatar)
    {
        if ($this->logo_url) {
            StorageMicroService::destroy($this->logo_url);
        }
        $storage_file = StorageMicroService::uploadFile($uploadedAvatar, 'merchants');

        $this->logo_url = $storage_file['url'];
        $this->save();
    }

    public function deleteLogo()
    {
        if (!$this->logo_url) {
            return;
        }
        StorageMicroService::destroy($this->logo_url);

        $this->logo_url = null;
        $this->save();
    }

    public function uploadFile(UploadedFile $uploadedFile, $type)
    {
        $storage_file = StorageMicroService::uploadFile($uploadedFile, 'merchants');
        $merchant_file = new File();
        $merchant_file->file_type = $type;
        $merchant_file->mime_type = $storage_file['mime_type'];
        $merchant_file->size = $storage_file['size'];
        $merchant_file->url = $storage_file['url'];
        $merchant_file->merchant_id = $this->id;
        $merchant_file->save();

        return $merchant_file;
    }

    public function deleteFile($file_id)
    {
        $file = $this->files()->find($file_id);
        if (!$file) {
            return;
        }

        StorageMicroService::destroy($file->url);
        $file->delete();
    }
}
