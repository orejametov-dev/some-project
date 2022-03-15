<?php

declare(strict_types=1);

namespace App\HttpRepositories\Alifshop;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\Collection;

class AlifshopHttpRepository
{
    public function storeOrUpdateConditions(int $company_id, Collection $conditions): mixed
    {
        $client = self::getHttpClient();
        $response = $client->request('POST', '/gate/service-merchants/companies/' . $company_id . '/conditions', [
            'json' => compact('conditions'),
        ]);

        return self::parseResponse($response);
    }

    protected function parseResponse(mixed $response): mixed
    {
        return json_decode($response->getBody()->getContents(), true);
    }

    protected function getHttpClient(): HttpClient
    {
        return new HttpClient([
            'base_uri' => config('local_services.alifshop.domain'),
            'headers' => [
                'Access-Token' => config('local_services.alifshop.token'),
                'Accept' => 'application/json',
            ],
        ]);
    }
}
