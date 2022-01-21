<?php

namespace App\HttpRepositories\CoreHttpRepositories;

use App\HttpRepositories\CoreHttpResponse\ApplicationDataResponse;
use App\HttpRepositories\CoreHttpResponse\ApplicationDataByContractNumberResponse;
use Carbon\Carbon;
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

    public function getApplicationDataByContractNumber($contract_number)
    {
        $data = $this->getHttpClient()->get("applications/$contract_number")->throw()->json();

        return new ApplicationDataResponse(
            id: (int) $data['id'],
            merchant_id: (int) $data['merchant_id'],
            store_id: (int) $data['store_id'],
            client_id: (int) $data['client']['id'],
            client_name: (string) $data['client']['name'],
            client_surname: (string) $data['client']['surname'],
            client_patronymic: (string) $data['client']['patronymic'],
            phone: (string) $data['client']['phone'],
            application_items: (array) $data['application_items'],
            post_or_pre_created_by_id: (int) $data['merchant_engaged_by']['id'],
            post_or_pre_created_by_name: (string) $data['merchant_engaged_by']['name'],
            credit_contract_date: Carbon::parse($data['contract_date'])
        );
    }

    public function getApplicationDataByApplicationId($application_id)
    {
        $data =  $this->getHttpClient()->get("applications/$application_id")->throw()->json();

        return new ApplicationDataResponse(
        id: (int) $data['id'],
        merchant_id: (int) $data['merchant_id'],
        store_id: (int) $data['store_id'],
        client_id: (int) $data['client']['id'],
        client_name: (string) $data['client']['name'],
        client_surname: (string) $data['client']['surname'],
        client_patronymic: (string) $data['client']['patronymic'],
        phone: (string) $data['client']['phone'],
        application_items: (array) $data['application_items'],
        post_or_pre_created_by_id: (int) $data['merchant_engaged_by']['id'],
        post_or_pre_created_by_name: (string) $data['merchant_engaged_by']['name'],
        application_created_at: Carbon::parse($data['created_at'])
    );
    }

    public function getAmountOfMerchantSales()
    {
        return $this->getHttpClient()->get('merchant-sales')->throw()->json();
    }

    public function getApplicationConditionId($condition_id)
    {
        return $this->getHttpClient()->get("applications/count", [
            'condition_id' => $condition_id
        ])
            ->throw()
            ->json();
    }

    protected function getHttpClient(): PendingRequest
    {
        return Http::baseUrl(config('local_services.service_core.domain') . '/')
            ->withHeaders([
                'Accept' => 'application/json',
                'Access-Token' => config('local_services.service_core.service_token'),
                'Content-Type' => 'application/json'
            ]);
    }
}
