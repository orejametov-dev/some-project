<?php

namespace App\Services\Alifshop;

use GuzzleHttp\Client as HttpClient;

class AlifshopService
{
    public function storeOrUpdateConditions(int $company_id, $conditions)
    {
        $client = self::createRequest();
        $response = $client->request('POST', '/gate/service-merchants/companies/' . $company_id . '/conditions', [
            'json' => compact('conditions')
        ]);
        return self::parseResponse($response);
    }

    protected static function createRequest()
    {
        return new HttpClient([
            'base_uri' => config('local_services.alifshop.domain'),
            'headers' => [
                'Accept' => 'application/json'
            ],
        ]);
    }

    protected static function parseResponse($response)
    {
        return json_decode($response->getBody()->getContents(), true);
    }
}
