<?php

return [
    'gateway_access_secret' => env('GATEWAY_ACCESS_SECRET'),
    'services_ocr' => [
        'domain' => env('SERVICES_OCR_DOMAIN'),
        'token' => env('SERVICES_OCR_TOKEN'),
    ],
    'services_notify' => [
        'domain' => env('SERVICES_NOTIFY'),
        'access_token' => env('SERVICES_NOTIFY_ACCESS_TOKEN')
    ],
    'services_cards' => [
        'domain' => env('SERVICES_CARDS'),
        'access_token' => env('SERVICES_CARDS_ACCESS_TOKEN')
    ],
    'services_tickets' => [
        'domain' => env('SERVICES_TICKETS'),
        'access_token' => env('SERVICES_TICKETS_ACCESS_TOKEN'),
        'problem_subject_id' => env('SERVICES_TICKETS_PROBLEM_SUBJECT_ID')
    ],
    'services_storage' => [
        'domain' => env('SERVICES_STORAGE'),
        'access_token' => env('STORAGE_ACCESS_TOKEN')
    ],
    'services_calls' => [
        'domain' => env('SERVICES_CALLS')
    ],
    'services_law' => [
        'domain' => env('SERVICES_LAW'),
        'token' => env('SERVICES_LAW_TOKEN'),
    ],
    'payme_debit_service' => [
        'login' => env('PAYME_LOGIN'),
        'domain' => env('PAYME_DEBIT_DOMAIN'),
        'kass_id' => env('PAYME_DEBIT_KASSA_ID'),
        'key' => env('PAYME_DEBIT_KEY')
    ],
    'payme_billing_service' => [
        'login' => env('PAYME_LOGIN'),
        'kass_id' => env('PAYME_BILLING_KASSA_ID'),
        'key' => env('PAYME_BILLING_KEY'),
        'test_key' => env('PAYME_BILLING_TEST_KEY')
    ],
    'click_billing' => [
        'merchant_api' => env('CLICK_MERCHANT_API'),
        'key' => env('CLICK_BILLING_SECRET_KEY'),
        'merchant_id' => env('CLICK_BILLING_MERCHANT_ID'),
        'service_id' => env('CLICK_BILLING_SERVICE_ID'),
        'user_id' => env('CLICK_BILLING_MERCHANT_USER_ID'),
    ],
    'paymo_service' => [
        'domain' => env('PAYMO_DOMAIN'),
        'user' => env('PAYMO_USER'),
        'password' => env('PAYMO_PASSWORD'),
        'store_id' => env('PAYMO_STORE_ID'),
        'terminal_id' => env('PAYMO_TERMINAL_ID')
    ],
    'report_bot' => [
        'uri' => env('REPORT_BOT_URI'),
    ],
    'billing_token' => env('BILLING_TOKEN', 'VHZLy34l8r'),
    'uzcard' => [
        'domain' => env('PROXY_TO_UZCARD_DOMAIN'),
        'login' => env('PROXY_TO_UZCARD_LOGIN'),
        'password' => env('PROXY_TO_UZCARD_PASSWORD')
    ],
    'cbu_service' => [
        'domain' => env('CBU_DOMAIN'),
    ],
    'royxat' => [
        'domain' => env('ROYXAT_DOMAIN'),
        'token' => env('ROYXAT_TOKEN'),
    ],
    'telegram' => [
        'api_url' => env('TELEGRAM_API_URL'),
        'compliance_bot_token' => env('TELEGRAM_COMPLIANCE_BOT_TOKEN'),
        'compliance_group_chat_id' => env('TELEGRAM_COMPLIANCE_GROUP_CHAT_ID'),
        'notify_bot' => [
            'url' => env('NOTIFY_BOT_URL')
        ],
    ],
    'online' => [
        'redirect_url' => env('ONLINE_REDIRECT_URL')
    ],
    'cb_service' => [
        'proxy_domain' => env('CB_PROXY_DOMAIN'),
        'proxy_login' => env('CB_PROXY_LOGIN'),
        'proxy_password' => env('CB_PROXY_PASSWORD'),
        'login' => env('CB_LOGIN'),
        'password' => env('CB_PASSWORD'),
        'code' => env('CB_CODE'),
        'head' => env('CB_HEAD')
    ],
    'alifshop' => [
        'domain' => env('ALIFSHOP_DOMAIN'),
    ],
    'credits' => [
        'domain' => env('SERVICE_CREDITS_DOMAIN'),
        'token' => env('SERVICE_CREDITS_ACCESS_TOKEN'),
    ],
];
