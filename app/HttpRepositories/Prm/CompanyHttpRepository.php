<?php

namespace App\HttpRepositories\Prm;

use App\HttpRepositories\HttpResponses\Prm\CompanyHttpResponse;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class CompanyHttpRepository
{
    public function getCompanyById(int $company_id): ?CompanyHttpResponse
    {
        $result = $this->getHttpClient()->get("companies/$company_id")->throw()->json();

        return CompanyHttpResponse::fromArray($result);
    }

    public function getCompanyByName(string $name): ?CompanyHttpResponse
    {
        $result = $this->getHttpClient()->get('companies/company-by-name', ['name' => $name])->throw()->json();

        return CompanyHttpResponse::fromArray($result);
    }

    public function createCompany(string $name, string $legal_name, string $legal_name_prefix): ?CompanyHttpResponse
    {
        $result = $this->getHttpClient()->post('companies', [
            'name' => $name,
            'legal_name' => $legal_name,
            'legal_name_prefix' => $legal_name_prefix,
        ])->throw()->json();

        return CompanyHttpResponse::fromArray($result);
    }

    public function checkCompanyToExistByName(string $name): bool
    {
        $result = $this->getHttpClient()->get('companies/company-by-name', ['name' => $name])->throw()->json();

        return $result !== null;
    }

    public function setStatusExist(int $id, string $company_module = null)
    {
        return $this->getHttpClient()->post('companies/' . $id . '/status-exists', [
            'company_module' => is_null($company_module) ? 'azo' : $company_module,
        ])->throw()->json();
    }

    public function setStatusNotActive(int $id, string $company_module = null)
    {
        return $this->getHttpClient()->post('companies/' . $id . '/status-not-active', [
            'company_module' => is_null($company_module) ? 'azo' : $company_module,
        ])->throw()->json();
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
