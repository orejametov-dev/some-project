<?php

namespace App\HttpRepositories\Core;

use App\HttpRepositories\HttpResponses\Core\ApplicationIdApplicationDataResponse;
use App\HttpRepositories\HttpResponses\Core\CreditNumberApplicationDataResponse;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class CoreHttpRepository
{
    public function getMerchantApplicationsAndClientsCountByRange($merchant_id, $from_date, $to_date)
    {
        return $this->getHttpClient()->get('merchant-activity', [
            'from_date' => $from_date,
            'to_date' => $to_date,
            'merchant_id' => $merchant_id,
        ])->json();
    }

    public function getStoreApplicationsAndClientsCountByRange($store_id, $from_date, $to_date)
    {
        return $this->getHttpClient()->get('merchant-activity', [
            'from_date' => $from_date,
            'to_date' => $to_date,
            'store_id' => $store_id,
        ])->json();
    }

    public function getApplicationDataByContractNumber($contract_number): ?CreditNumberApplicationDataResponse
    {
        $data = $this->getHttpClient()->get("applications/$contract_number")->throw()->json();

        return CreditNumberApplicationDataResponse::fromArray($data);
    }

    public function getApplicationDataByApplicationId($application_id): ?ApplicationIdApplicationDataResponse
    {
        $data = $this->getHttpClient()->get("applications/$application_id")->throw()->json();

        return ApplicationIdApplicationDataResponse::fromArray($data);
    }

    public function getAmountOfMerchantSales()
    {
        return $this->getHttpClient()->get('merchant-sales')->throw()->json();
    }

    public function getApplicationConditionId($condition_id)
    {
        return $this->getHttpClient()->get('applications/count', [
            'condition_id' => $condition_id,
        ])
            ->throw()
            ->json();
    }

    public function checkApplicationToExistByConditionId($condition_id): bool
    {
        $result = $this->getHttpClient()->get('applications/count', [
            'condition_id' => $condition_id,
        ])
            ->throw()
            ->json();

        return (bool) $result;
    }

    public function checkClientToExistsByClientId($client_id): bool
    {
        $result = $this->getHttpClient()->get("clients/$client_id")
            ->throw()
            ->json();

        return (bool) $result;
    }

    protected function getHttpClient(): PendingRequest
    {
        return Http::baseUrl(config('local_services.service_core.domain') . '/')
            ->withHeaders([
                'Accept' => 'application/json',
                'Access-Token' => config('local_services.service_core.service_token'),
                'Content-Type' => 'application/json',
            ]);
    }
}
