<?php

namespace App\HttpServices\Telegram;

use Illuminate\Support\Facades\Http;

class TelegramService
{
    public static function getUpdates(array $options): array
    {
        return Http::baseUrl(
            config('local_services.telegram.api_url') . 'bot' . config('local_services.telegram.compliance_bot_token') . '/'
        )
            ->get('getUpdates', $options)
            ->throw()
            ->json();
    }
}
