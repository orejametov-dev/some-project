<?php


namespace App\HttpServices\Warehouse;


use App\Exceptions\BusinessException;
use Illuminate\Support\Facades\Http;

class WarehouseService
{
    public static function checkDuplicateSKUs($merchant_id)
    {
        try {
            return static::http()->get('/gate/items/check-duplications', [
                'merchant_id' => $merchant_id
            ]);
        } catch (BusinessException $e) {
            throw new BusinessException($e->getMessage(), 'duplicate_sku');
        }
    }

    protected static function http()
    {;
        return Http::baseUrl(config('local_services.warehouse.domain') . '/')
            ->withHeaders([
                'Accept' => 'application/json',
                'Access-Token' => config('local_services.warehouse.access_token')
            ]);
    }
}
