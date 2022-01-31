<?php

namespace App\DTOs\Conditions;

class UpdateConditionDTO
{
    public function __construct(
        public ?array $store_ids,
        public ?int $duration,
        public int $commission,
        public ?string $special_offer,
        public ?int $event_id,
        public int $discount,
    )
    {
    }
}
