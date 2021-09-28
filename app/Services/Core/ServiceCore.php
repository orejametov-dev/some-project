<?php

namespace App\Services\Core;

use App\Exceptions\ServiceCoreException;
use App\Modules\Core\Models\WebService;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

class ServiceCore
{
    public static function storeHook($body, $keyword, $action, $class, $model)
    {
        ServiceCore::request('POST', 'model-hooks', [
            'body' => $body,
            'keyword' => $keyword,
            'action' => $action,
            'class' => $class,
            'model' => [
                'id' => $model->id,
                'table_name' => $model->getTable()
            ]
        ]);
    }


    public static function request($method, $route, $params, $should_return_response = false)
    {
        $token = app(WebService::class)->token;
        $client = self::createRequest();
        $key = $method == 'GET' ? 'query' : 'json';
        try {
            $response = $client->request($method, $route, [
                'headers' => [
                    'Service-Token' => $token,
                    'Access-Token' => config('local_service.service_core.service_token'),
                ],
                $key => $params
            ]);
            if (!$should_return_response) {
                return self::parseResponse($response);
            } else {
                return $response;
            }
        } catch (RequestException $e) {
            throw new ServiceCoreException($e->getResponse()->getBody()->getContents(), $e->getCode());
        }
    }

    protected static function createRequest(): ClientInterface
    {
        return new HttpClient([
            'base_uri' => config('local_services.service_core.domain'),
            'headers' => [
                'Accept' => 'application/json',
                'Content-type' => 'application/json'
            ],
        ]);
    }

    protected static function parseResponse(ResponseInterface $response)
    {
        return json_decode($response->getBody()->getContents());
    }
}
