<?php

namespace App\HttpRepositories\Hooks;

use App\HttpRepositories\Hooks\DTO\HookData;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class HooksHttpRepository
{
    public function store(HookData $data): mixed
    {
        $response = $this->getHttpClient()
            ->post('hooks', (array) $data)
            ->throw();

        return $response->json();
    }

    protected function getHttpClient(): PendingRequest
    {
        return Http::baseUrl(config('local_services.service_hook.domain') . '/')
            ->withHeaders([
                'Accept' => 'application/json',
                'Access-Token' => config('local_services.service_hook.access_token'),
                'Content-Type' => 'application/json',
            ]);
    }
}
