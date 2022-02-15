<?php

namespace App\HttpRepositories\Prm;

use App\HttpRepositories\HttpResponses\Prm\CompanyUserHttpResponse;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class CompanyUserHttpRepository
{
    public function createCompanyUser(int $user_id, int $company_id, string $phone, string $full_name): CompanyUserHttpResponse
    {
        $result = $this->getHttpClient()->post('companies/users', [
            'user_id' => $user_id,
            'company_id' => $company_id,
            'phone' => $phone,
            'full_name' => $full_name,
        ])->throw()->json();

        return CompanyUserHttpResponse::fromArray($result);
    }

    public function getCompanyUserByUserId($user_id): ?CompanyUserHttpResponse
    {
        $result = $this->getHttpClient()->get('companies/users/get-user-id', [
            'user_id' => $user_id,
        ])->throw()->json();

        if ($result === null) {
            return null;
        }

        return CompanyUserHttpResponse::fromArray($result);
    }

    public function checkCompanyUserToExistByUserId($user_id): bool
    {
        $result = $this->getHttpClient()->get('companies/users/get-user-id', [
            'user_id' => $user_id,
        ])->throw()->json();

        return $result !== null;
    }

    private function getHttpClient(): PendingRequest
    {
        return Http::baseUrl(config('local_services.service_prm.domain') . '/api/gate/')
            ->withHeaders([
                'Accept' => 'application/json',
                'Access-Token' => config('local_services.service_prm.service_token'),
                'Content-Type' => 'application/json',
            ]);
    }
}
