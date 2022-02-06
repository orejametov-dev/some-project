<?php

return [
    'gateway_access_secret' => env('GATEWAY_ACCESS_SECRET'),
    'access_token' => env('ACCESS_TOKEN'),
    'services_tickets' => [
        'domain' => env('SERVICES_TICKETS'),
        'access_token' => env('SERVICES_TICKETS_ACCESS_TOKEN'),
        'problem_subject_id' => env('SERVICES_TICKETS_PROBLEM_SUBJECT_ID'),
    ],
    'services_storage' => [
        'domain' => env('SERVICES_STORAGE'),
        'access_token' => env('STORAGE_ACCESS_TOKEN'),
    ],
    'telegram' => [
        'api_url' => env('TELEGRAM_API_URL'),
        'compliance_bot_token' => env('TELEGRAM_COMPLIANCE_BOT_TOKEN'),
        'compliance_group_chat_id' => env('TELEGRAM_COMPLIANCE_GROUP_CHAT_ID'),
        'notify_bot' => [
            'url' => env('NOTIFY_BOT_URL'),
        ],
    ],
    'alifshop' => [
        'domain' => env('ALIFSHOP_DOMAIN'),
        'token' => env('ALIFSHOP_TOKEN'),
    ],
    'service_core' => [
        'domain' => env('SERVICE_CORE_URL'),
        'service_token' => env('SERVICE_CORE_TOKEN'),
    ],
    'time_logger' => env('TIME_LOGGER'),

    'service_hook' => [
        'domain' => env('SERVICE_HOOK_DOMAIN'),
        'access_token' => env('SERVICE_HOOK_ACCESS_TOKEN'),
    ],
    'service_auth' => [
        'domain' => env('SERVICE_AUTH_DOMAIN'),
        'access_token' => env('SERVICE_AUTH_ACCESS_TOKEN'),
    ],
    'warehouse' => [
        'domain' => env('SERVICE_WAREHOUSE_DOMAIN'),
        'access_token' => env('SERVICE_WAREHOUSE_ACCESS_TOKEN'),
    ],
    'service_notify' => [
        'domain' => env('SERVICES_NOTIFY'),
        'access_token' => env('SERVICES_NOTIFY_ACCESS_TOKEN'),
    ],
    'service_prm' => [
        'domain' => env('SERVICE_PRM_URL'),
        'service_token' => env('SERVICE_PRM_TOKEN'),
    ],
];
