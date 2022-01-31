<?php

declare(strict_types=1);

namespace App\DTOs\Conditions;

use Alifuz\Utils\Parser\ParseDataTrait;

class UpdateConditionDTO
{
    use ParseDataTrait;

    public function __construct(
        public int $condition_id,
        public ?array $store_ids,
        public ?int $duration,
        public int $commission,
        public ?string $special_offer,
        public ?int $event_id,
        public int $discount,
    )
    {
    }

    public static function fromArray(int $condition_id , array $data): self
    {
        return new self(
            self::parseInt($condition_id),
            self::parseNullableArray($data['store_ids']),
            $data['duration'] ? self::parseInt($data['duration']) : 0,
            self::parseInt($data['commission']),
            self::parseNullableString($data['special_offer']),
            self::parseNullableInt($data['event_id']),
            self::parseInt($data['discount'])
        );
    }
}
