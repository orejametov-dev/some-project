<?php

namespace App\DTOs\Conditions;

use Carbon\Carbon;

class StoreConditionDTO
{
    public function __construct(
        public ?int $merchant_id,
        public array $store_ids,
        public ?int $duration,
        public int $commission,
        public ?string $special_offer,
        public ?int $event_id,
        public int $discount,
        public bool $post_merchant,
        public bool $post_alifshop,
        public ?Carbon $started_at,
        public ?Carbon $finished_at,
        public int $user_id,
        public string $user_name
    )
    {
    }
}
