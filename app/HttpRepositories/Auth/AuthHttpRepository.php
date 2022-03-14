<?php

declare(strict_types=1);

namespace App\HttpRepositories\Auth;

use App\HttpRepositories\HttpResponses\Auth\AuthHttpResponse;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class AuthHttpRepository
{
    const ACTIVATE_MERCHANT_ROLE = 'ACTIVATE';
    const DEACTIVATE_MERCHANT_ROLE = 'DEACTIVATE';
    const AZO_MERCHANT_ROLE = 'Merchant';

    public function store(int $user_id): mixed
    {
        $response = $this->getHttpClient()
            ->post('users/' . $user_id . '/role', [
                'role_id' => 'Merchant',
            ])
            ->throw();

        return $response->json();
    }

    public function remove(int $user_id): mixed
    {
        $response = $this->getHttpClient()
            ->delete('users/' . $user_id . '/role', [
                'role_id' => 'Merchant',
            ])
            ->throw();

        return $response->json();
    }

    public function checkUserToExistById(int $user_id): bool
    {
        $result = $this->getHttpClient()->get("users/$user_id");
        if ($result->status() === 404) {
            return false;
        }

        return (bool) $result->json();
    }

    public function getUserById(int $user_id): ?AuthHttpResponse
    {
        $result = $this->getHttpClient()->get("users/$user_id");
        if ($result->status() === 404) {
            return null;
        }

        return AuthHttpResponse::fromArray($result->json());
    }

    public function getUserByPhone(string $phone): mixed
    {
        return $this->getHttpClient()->get('users/exists', [
            'phone' => $phone,
            'role' => 'Merchant',
        ])
            ->throw()
            ->json();
    }

    public function createUser(string $name, string $phone, string $password): mixed
    {
        return $this->getHttpClient()->post('users', [
            'phone' => $phone,
            'name' => $name,
            'password' => $password,
            'roles' => 'Merchant',
        ])
            ->throw()
            ->json();
    }

    private function getHttpClient(): PendingRequest
    {
        return Http::baseUrl(config('local_services.service_auth.domain') . '/api/gate/')
            ->acceptJson()
            ->withHeaders([
                'X-Access-Token' => config('local_services.service_auth.access_token'),
            ]);
    }
}
