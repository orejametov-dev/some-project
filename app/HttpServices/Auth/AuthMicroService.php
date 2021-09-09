<?php

namespace App\HttpServices\Auth;

use App\HttpServices\Hooks\DTO\HookData;
use Illuminate\Support\Facades\Http;

class AuthMicroService
{
    const ACTIVATE_MERCHANT_ROLE = "ACTIVATE";
    const DEACTIVATE_MERCHANT_ROLE = "DEACTIVATE";

    public function store($user_id)
    {
        $response = Http::baseUrl(config('local_services.service_auth.domain') . '/api/gate/')
            ->acceptJson()
            ->withHeaders([
                'X-Access-Token' => config('local_services.service_auth.access_token')
            ])
            ->post('users/' . $user_id . '/role', [
                'role_id' => 'Merchant'
            ])
            ->throw();

        return $response->json();
    }

    public function remove($user_id)
    {
        $response = Http::baseUrl(config('local_services.service_auth.domain') . '/api/gate/')
            ->acceptJson()
            ->withHeaders([
                'X-Access-Token' => config('local_services.service_auth.access_token')
            ])
            ->delete('users/' . $user_id . '/role', [
                'role_id' => 'Merchant'
            ])
            ->throw();

        return $response->json();
    }
}
