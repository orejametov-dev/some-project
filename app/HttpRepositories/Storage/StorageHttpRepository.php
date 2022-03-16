<?php

namespace App\HttpRepositories\Storage;

use App\HttpRepositories\HttpResponses\Storage\UploadFileResponse;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;

class StorageHttpRepository
{
    public function uploadFile(UploadedFile $file, string $type): UploadFileResponse
    {
        $response = $this->getHttpClient()
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

        return UploadFileResponse::fromArray($response);
    }

    public function destroy(string $url): void
    {
        $this->getHttpClient()
            ->delete($url)
            ->throw()
            ->json();
    }

    protected function getHttpClient(): PendingRequest
    {
        return Http::baseUrl(config('local_services.services_storage.domain') . '/')
            ->withHeaders([
                'Accept' => 'application/json',
                'Access-Token' => config('local_services.services_storage.access_token'),
            ]);
    }
}
