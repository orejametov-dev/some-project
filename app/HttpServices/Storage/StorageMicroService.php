<?php

declare(strict_types=1);

namespace App\HttpServices\Storage;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;

class StorageMicroService
{
    public static function uploadFile(UploadedFile $file, string $type): mixed
    {
        return static::http()
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

    public static function destroy(string $url): mixed
    {
        return static::http()
            ->delete($url)
            ->throw()
            ->json();
    }

    protected static function http(): PendingRequest
    {
        return Http::baseUrl(config('local_services.services_storage.domain') . '/')
            ->withHeaders([
                'Accept' => 'application/json',
                'Access-Token' => config('local_services.services_storage.access_token'),
            ]);
    }
}
