<?php

namespace App\HttpServices\Notify;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class NotifyMicroService
{
    public const CB_AGREEMENT = 'CB_AGREEMENT';
    public const CLIENT_AZO = 'CLIENT_AZO';
    public const CREDIT_CONFIRMATION = 'CREDIT_CONFIRMATION';
    public const REJECT_CASE = 'REJECT_CASE';
    public const COMMON = 'COMMON';
    public const PROBLEM_CASE = 'PROBLEM_CASE';

    public static function sendSms(string $phone, string $body, string $tag = self::COMMON): array
    {
        $http = self::http();

        return $http->post('api/notification-by-sms', compact('phone', 'body', 'tag'))
            ->throw()
            ->json();
    }

    public static function sendDistribution(string $phone, string $body, string $tag = self::COMMON): array
    {
        $http = self::http();

        return $http->post('/api/notification-by-distribution', compact('phone', 'body', 'tag'))
            ->throw()
            ->json();
    }

    public static function call(string $phone, string $body, string $tag = self::COMMON): array
    {
        $http = self::http();

        return $http->post('api/notification-by-call', compact('phone', 'body', 'tag'))
            ->throw()
            ->json();
    }

    protected static function http(): PendingRequest
    {
        return Http::baseUrl(config('local_services.service_notify.domain'))
            ->acceptJson()
            ->withHeaders([
                'Access-Token' => config('local_services.service_notify.access_token'),
            ]);
    }
}
