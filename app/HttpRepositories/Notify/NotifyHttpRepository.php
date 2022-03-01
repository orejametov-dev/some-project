<?php

namespace App\HttpRepositories\Notify;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class NotifyHttpRepository
{
    public const CB_AGREEMENT = 'CB_AGREEMENT';
    public const CLIENT_AZO = 'CLIENT_AZO';
    public const CREDIT_CONFIRMATION = 'CREDIT_CONFIRMATION';
    public const REJECT_CASE = 'REJECT_CASE';
    public const COMMON = 'COMMON';
    public const PROBLEM_CASE = 'PROBLEM_CASE';

    public function sendSms($phone, $body, $tag = self::COMMON): array
    {
        return $this->getHttpClient()
            ->post('api/notification-by-sms', compact('phone', 'body', 'tag'))
            ->throw()
            ->json();
    }

    public function sendDistribution($phone, $body, $tag = self::COMMON): array
    {
        return $this->getHttpClient()
            ->post('/api/notification-by-distribution', compact('phone', 'body', 'tag'))
            ->throw()
            ->json();
    }

    public function call($phone, $body, $tag = self::COMMON): array
    {
        return $this->getHttpClient()
            ->post('api/notification-by-call', compact('phone', 'body', 'tag'))
            ->throw()
            ->json();
    }

    private function getHttpClient(): PendingRequest
    {
        return Http::baseUrl(config('local_services.service_notify.domain'))
            ->acceptJson()
            ->withHeaders([
                'Access-Token' => config('local_services.service_notify.access_token'),
            ]);
    }
}
