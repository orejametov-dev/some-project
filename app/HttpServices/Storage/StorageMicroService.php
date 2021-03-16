<?php

namespace App\HttpServices\Storage;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;

class StorageMicroService
{
    public static function uploadFile(UploadedFile $file, $client_number = ' ')
    {
        return static::http()
            ->attach(
                'file',
                file_get_contents($file->path()),
                $file->getClientOriginalName()
            )
            ->attach(
                'client_number',
                $client_number
            )
            ->post('files')
            ->throw()
            ->json();
    }

    public static function destroy(string $url)
    {
        return static::http()
            ->delete($url)
            ->throw()
            ->json();
    }

    protected static function http()
    {;
        return Http::baseUrl(config('local_services.services_storage.domain') . '/')
            ->withHeaders([
                'Accept' => 'application/json',
                'Access-Token' => config('local_services.services_storage.access_token')
            ]);
    }
}
