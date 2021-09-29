<?php

namespace App\HttpServices\Core;

use Illuminate\Support\Facades\Http;

class CoreService
{
    public function getMerchantApplicationsAndClientsCountByRange($merchant_id, $from_date, $to_date)
    {
        return static::http()->get('merchant-activity', [
            'from_date' => $from_date,
            'to_date' => $to_date,
            'merchant_id' => $merchant_id,
        ])->json();
    }

    public function getStoreApplicationsAndClientsCountByRange($store_id, $from_date, $to_date)
    {
        return static::http()->get('merchant-activity', [
            'from_date' => $from_date,
            'to_date' => $to_date,
            'store_id' => $store_id,
        ])->json();
    }

    public static function getApplicationDataByContractNumber($contract_number)
    {
        return static::http()->get( "applications/$contract_number")->throw()->json();
    }

    public static function  getApplicationDataByApplicationId($application_id)
    {
        return static::http()->get("applications/$application_id")->throw()->json();
    }

    public static function getAmountOfMerchantSales()
    {
        return static::http()->get('merchant-sales')->throw()->json();
    }

    public static function getApplicationConditionId($condition_id)
    {
        return static::http()->get("applications/count", [
            'condition_id' => $condition_id
        ])
            ->throw()
            ->json();
    }

    protected static function http()
    {
        return Http::baseUrl(config('local_services.service_core.domain') . '/')
            ->withHeaders([
                'Accept' => 'application/json',
                'Access-Token' => config('local_services.service_core.service_token'),
                'Content-Type' => 'application/json'
            ]);
    }
}
