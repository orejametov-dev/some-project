<?php

namespace App\HttpRepositories\Alifshop;

use GuzzleHttp\Client as HttpClient;

class AlifshopHttpRepository
{
    public function storeOrUpdateConditions(int $company_id, $conditions)
    {
        $client = self::getHttpClient();
        $response = $client->request('POST', '/gate/service-merchants/companies/' . $company_id . '/conditions', [
            'json' => compact('conditions')
        ]);
        return self::parseResponse($response);
    }

    protected function parseResponse($response)
    {
        return json_decode($response->getBody()->getContents(), true);
    }

    protected function getHttpClient()
    {
        return new HttpClient([
            'base_uri' => config('local_services.alifshop.domain'),
            'headers' => [
                'Access-Token' => config('local_services.alifshop.token'),
                'Accept' => 'application/json'
            ],
        ]);
    }
}
