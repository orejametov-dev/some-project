<?php

namespace App\HttpServices\Hooks;

use App\HttpServices\Hooks\DTO\HookData;
use Illuminate\Support\Facades\Http;

class HooksMicroService
{
    public function store(HookData $data)
    {
        $response = Http::baseUrl(config('local_services.service_hook.domain'))
            ->acceptJson()
            ->withHeaders([
                'Access-Token' => config('local_services.service_hook.access_token')
            ])
            ->post('hooks', (array)$data)
            ->throw();

        return $response->json();
    }
}
