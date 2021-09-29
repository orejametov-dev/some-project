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
