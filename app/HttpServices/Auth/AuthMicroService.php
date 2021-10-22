<?php

namespace App\HttpServices\Auth;

use App\HttpServices\Hooks\DTO\HookData;
use Illuminate\Support\Facades\Http;

class AuthMicroService
{
    const ACTIVATE_MERCHANT_ROLE = "ACTIVATE";
    const DEACTIVATE_MERCHANT_ROLE = "DEACTIVATE";
    const AZO_MERCHANT_ROLE = 'Merchant';

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

    public static function getUserById($user_id)
    {
        return static::http()->get( "users/$user_id")->throw()->json();
    }

    public static function getUserByPhone($phone)
    {
        return static::http()->get('users/exists' , [
            'phone' => $phone,
            'role' => 'Merchant'
        ])
            ->throw()
            ->json();
    }

    public static function createUser(string $name, string $phone, string $password)
    {
        return static::http()->post('users' , [
            'phone' => $phone,
            'name' => $name,
            'password' => $password,
            'roles' => 'Merchant'
        ])
            ->throw()
            ->json();
    }

    protected static function http()
    {
        return Http::baseUrl(config('local_services.service_auth.domain') . '/api/gate/')
            ->withHeaders([
                'X-Access-Token' => config('local_services.service_auth.access_token')
            ]);
    }
}
