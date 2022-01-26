<?php

namespace App\HttpRepositories\HttpResponses\Core;

use Carbon\Carbon;

class ApplicationDataResponse
{
    public function __construct(
        public int    $id,
        public int    $merchant_id,
        public int    $store_id,
        public int    $client_id,
        public string $client_name,
        public string $client_surname,
        public string $client_patronymic,
        public string $phone,
        public ?array $application_items,
        public int    $post_or_pre_created_by_id,
        public string $post_or_pre_created_by_name,
        public ?Carbon $application_created_at = null,
        public ?Carbon $credit_contract_date = null,
    )
    {
    }
}
