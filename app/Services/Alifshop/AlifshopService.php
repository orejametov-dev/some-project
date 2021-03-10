<?php

namespace App\Services\Alifshop;

use GuzzleHttp\Client as HttpClient;

class AlifshopService
{
    public static function getItems($query, $token)
    {
        $http_client = new HttpClient([
            'base_uri' => config('local_services.alifshop.domain'),
            'headers' => [
                'Accept' => 'application/json',
                'Token' => $token
            ]
        ]);

        $response = $http_client->request('GET', 'api/partner/items/show', [
            'json' => ['q' => $query]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function storeOrUpdateMerchant($merchant, $conditions = null)
    {
        $partner = [
            'name' => $merchant->name,
            'slug' => $merchant->alifshop_slug,
            'old_token' => $merchant->old_token ?? $merchant->token,
            'token' => $merchant->token,
            'information' => $merchant->information,
            'logo_path' => $merchant->logo_path
        ];

        $client = self::createRequest();
        $response = $client->request('POST', 'api/gate/service-core/partners', [
            'json' => compact('partner', 'conditions')
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
