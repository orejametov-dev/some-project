<?php

namespace App\HttpRepositories\Warehouse;

use App\Exceptions\BusinessException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class WarehouseHttpRepository
{
    public function checkDuplicateSKUs(int $merchant_id): void
    {
        $response = $this->getHttpClient()->get('/gate/items/check-duplications', [
            'merchant_id' => $merchant_id,
        ]);
        if ($response->clientError()) {
            throw new BusinessException($response->body(), 'duplicate_body');
        }
    }

    protected function getHttpClient(): PendingRequest
    {
        return Http::baseUrl(config('local_services.warehouse.domain') . '/')
            ->withHeaders([
                'Accept' => 'application/json',
                'Access-Token' => config('local_services.warehouse.access_token'),
            ]);
    }
}
