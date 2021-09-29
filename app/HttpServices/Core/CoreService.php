<?php

namespace App\HttpServices\Core;

use Illuminate\Support\Facades\Http;

class CoreService
{
    public function getMerchantApplicationsAndClientsCountByRange($merchant_id, $from_date, $to_date)
    {
        return static::http()->get('merchant-activity' , [
            'from_date' => $from_date,
            'to_date' => $to_date,
            'merchant_id' => $merchant_id,
        ])->json();
    }

    public function getStoreApplicationsAndClientsCountByRange($store_id, $from_date, $to_date)
    {
        return static::http()->get('merchant-activity' , [
            'from_date' => $from_date,
            'to_date' => $to_date,
            'store_id' => $store_id,
        ])->json();
    }

    public static function getAmountOfMerchantSales()
    {
        return static::http()->get('merchant-sales')->throw()->json();
    }

    public static function getUserById($user_id)
    {

    }

    public static function getUserEngagedById($engaged_by_id)
    {
        return static::http()->get( "users/$engaged_by_id")->throw()->json();
    }

    public static function getStoreUserId($user_id)
    {
        return static::http()->get( "users/$user_id")->throw()->json();
    }

    public static function getMaintainerId($maintainer_id)
    {
        return static::http()->get( "users/$maintainer_id")->throw()->json();
    }

    public static function getApplicationConditionId($condition_id)
    {
        return static::http()->get("applications/count" , [
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
                'Access-Token' => config('local_service.service_core.service_token'),
                'Content-Type' => 'application/json'
            ]);
    }
}
