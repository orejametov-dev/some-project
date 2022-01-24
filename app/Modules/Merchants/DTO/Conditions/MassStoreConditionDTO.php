<?php

namespace App\Modules\Merchants\DTO\Conditions;

use Carbon\Carbon;

class MassStoreConditionDTO
{
    public function __construct(
        public array $merchant_ids,
        public array $template_ids,
        public ?string $special_offer,
        public ?int $event_id,
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
