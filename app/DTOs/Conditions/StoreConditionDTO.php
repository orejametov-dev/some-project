<?php

declare(strict_types=1);

namespace App\DTOs\Conditions;

use Alifuz\Utils\Parser\ParseDataTrait;
use Carbon\Carbon;

class StoreConditionDTO
{
    use ParseDataTrait;

    public function __construct(
        public ?int $merchant_id,
        public ?array $store_ids,
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

    public static function fromArray(array $data): self
    {
        return new self(
            self::parseNullableInt($data['merchant_id']),
            self::parseNullableArray($data['store_ids']),
            array_key_exists('duration',$data) ? self::parseNullableInt($data['duration']) : 0,
            self::parseInt($data['commission']),
            self::parseNullableString($data['special_offer']),
            self::parseNullableInt($data['event_id']),
            self::parseInt($data['discount']),
            self::parseBool($data['post_merchant']),
            self::parseBool($data['post_alifshop']),
            isset($data['started_at']) ? Carbon::parse($data['started_at']) : null,
            isset($data['finished_at']) ? Carbon::parse($data['finished_at']) : null
        );
    }
}
