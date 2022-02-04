<?php

namespace App\HttpRepositories\Storage;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;

class StorageHttpRepository
{
    public function uploadFile(UploadedFile $file, $type)
    {
        return $this->getHttpClient()
            ->attach(
                'file',
                file_get_contents($file->path()),
                $file->getClientOriginalName()
            )
            ->attach(
                'type',
                $type
            )
            ->post('files')
            ->throw()
            ->json();
    }

    public function destroy(string $url)
    {
        return $this->getHttpClient()
            ->delete($url)
            ->throw()
            ->json();
    }

    protected function getHttpClient(): PendingRequest
    {;
        return Http::baseUrl(config('local_services.services_storage.domain') . '/')
            ->withHeaders([
                'Accept' => 'application/json',
                'Access-Token' => config('local_services.services_storage.access_token')
            ]);
    }
}
