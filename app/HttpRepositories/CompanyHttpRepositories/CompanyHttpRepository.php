<?php


namespace App\HttpRepositories\CompanyHttpRepositories;


use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class CompanyHttpRepository
{
    public function getCompanyById($company_id)
    {
        return $this->getHttpClient()->get("companies/$company_id")->throw()->json();
    }

    public function setStatusExist(int $id, string $company_module = null)
    {
        return $this->getHttpClient()->post('companies/' . $id . '/status-exists', [
            'company_module' => is_null($company_module) ? 'azo' : $company_module
        ])->throw()->json();
    }

    private function getHttpClient(): PendingRequest
    {
        return Http::baseUrl(config('local_services.service_prm.domain') . '/api/gate/')
            ->withHeaders([
                'Accept' => 'application/json',
                'Access-Token' => config('local_services.service_prm.service_token'),
                'Content-Type' => 'application/json'
            ]);
    }
}
