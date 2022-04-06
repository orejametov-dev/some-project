<?php

namespace App\HttpRepositories\Core;

use App\HttpRepositories\HttpResponses\Core\AmountOfMerchantSalesListResponse;
use App\HttpRepositories\HttpResponses\Core\ApplicationIdApplicationDataResponse;
use App\HttpRepositories\HttpResponses\Core\CreditNumberApplicationDataResponse;
use App\HttpRepositories\HttpResponses\Core\MerchantApplicationsAndClientsCountByRangeDataResponse;
use Carbon\Carbon;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class CoreHttpRepository
{
    public function getMerchantApplicationsAndClientsCountByRange(int $merchant_id, Carbon $from_date, Carbon $to_date): MerchantApplicationsAndClientsCountByRangeDataResponse
    {
        $result = $this->getHttpClient()->get('merchant-activity', [
            'from_date' => Carbon::parse($from_date)->format('Y-m-d'),
            'to_date' => Carbon::parse($to_date)->format('Y-m-d'),
            'merchant_id' => $merchant_id,
        ])->json();

        return MerchantApplicationsAndClientsCountByRangeDataResponse::fromArray($result['data']);
    }

    public function getStoreApplicationsAndClientsCountByRange(int $store_id, Carbon $from_date, Carbon $to_date): MerchantApplicationsAndClientsCountByRangeDataResponse
    {
        $result = $this->getHttpClient()->get('merchant-activity', [
            'from_date' => $from_date,
            'to_date' => $to_date,
            'store_id' => $store_id,
        ])->json();

        return MerchantApplicationsAndClientsCountByRangeDataResponse::fromArray($result);
    }

    public function getApplicationDataByContractNumber(string $contract_number): ? CreditNumberApplicationDataResponse
    {
        $data = $this->getHttpClient()->get("applications/$contract_number")->throw()->json();

        if (empty($data)) {
            return null;
        }

        return CreditNumberApplicationDataResponse::fromArray($data);
    }

    public function getApplicationDataByApplicationId(int $application_id): ?ApplicationIdApplicationDataResponse
    {
        $data = $this->getHttpClient()->get("applications/$application_id")->throw()->json();

        if (empty($data)) {
            return null;
        }

        return ApplicationIdApplicationDataResponse::fromArray($data);
    }

    public function getAmountOfMerchantSales(): AmountOfMerchantSalesListResponse
    {
        $result = $this->getHttpClient()->get('merchant-sales')->throw()->json();

        return AmountOfMerchantSalesListResponse::fromArray($result);
    }

    public function getApplicationConditionId(int $condition_id): mixed
    {
        return $this->getHttpClient()->get('applications/count', [
            'condition_id' => $condition_id,
        ])
            ->throw()
            ->json();
    }

    public function checkApplicationToExistByConditionId(int $condition_id): bool
    {
        $result = $this->getHttpClient()->get('applications/count', [
            'condition_id' => $condition_id,
        ])
            ->throw()
            ->json();

        return (bool) $result;
    }

    public function checkClientToExistsByClientId(int $client_id): bool
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
