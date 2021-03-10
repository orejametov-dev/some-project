<?php


namespace App\Services\Core;


use App\Exceptions\ServiceCoreException;
use App\Modules\Core\Models\WebService;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Psr\Http\Message\ResponseInterface;

class ServiceCore
{
    public static function request($method, $route, Request $request = null, $token = null, $should_return_response = false)
    {
        $token = isset($token) ? $token : app(WebService::class)->token;
        $params = isset($request) ? $request->all() : [];
        $client = self::createRequest();
        $key = $method == 'GET' ? 'query' : 'json';
        try {
            $response = $client->request($method, $route, [
                'headers' => [
                    'Service-Token' => config('local_services.service_core.service_token'),
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
