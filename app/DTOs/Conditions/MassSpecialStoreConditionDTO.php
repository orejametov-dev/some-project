<?php

namespace App\DTOs\Conditions;

use Carbon\Carbon;

class MassSpecialStoreConditionDTO
{
    public function __construct(
        public array $merchant_ids,
        public ?int $duration,
        public int $commission,
        public ?string $special_offer,
        public ?int $event_id,
        public int $discount,
        public bool $post_merchant,
        public bool $post_alifshop,
        public ?Carbon $started_at,
        public ?Carbon $finished_at,
    )
    {
    }
}
