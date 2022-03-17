<?php

declare(strict_types=1);

namespace App\HttpServices\Core;

use Carbon\Carbon;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class CoreService
{
    public function getMerchantApplicationsAndClientsCountByRange(int $merchant_id, Carbon $from_date, Carbon $to_date): mixed
    {
        return static::http()->get('merchant-activity', [
            'from_date' => $from_date,
            'to_date' => $to_date,
            'merchant_id' => $merchant_id,
        ])->json();
    }

    public function getStoreApplicationsAndClientsCountByRange(int $store_id, Carbon $from_date, Carbon $to_date): mixed
    {
        return static::http()->get('merchant-activity', [
            'from_date' => $from_date,
            'to_date' => $to_date,
            'store_id' => $store_id,
        ])->json();
    }

    public static function getApplicationDataByContractNumber(string $contract_number): mixed
    {
        return static::http()->get("applications/$contract_number")->throw()->json();
    }

    public static function getApplicationDataByApplicationId(int $application_id): mixed
    {
        return static::http()->get("applications/$application_id")->throw()->json();
    }

    public static function getAmountOfMerchantSales(): mixed
    {
        return static::http()->get('merchant-sales')->throw()->json();
    }

    public static function getApplicationConditionId(int $condition_id): mixed
    {
        return static::http()->get('applications/count', [
            'condition_id' => $condition_id,
        ])
            ->throw()
            ->json();
    }

    protected static function http(): PendingRequest
    {
        return Http::baseUrl(config('local_services.service_core.domain') . '/')
            ->withHeaders([
                'Accept' => 'application/json',
                'Access-Token' => config('local_services.service_core.service_token'),
                'Content-Type' => 'application/json',
            ]);
    }
}
