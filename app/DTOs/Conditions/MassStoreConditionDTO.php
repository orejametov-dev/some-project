<?php

declare(strict_types=1);

namespace App\DTOs\Conditions;

use Alifuz\Utils\Parser\ParseDataTrait;
use Carbon\Carbon;

class MassStoreConditionDTO
{
    use ParseDataTrait;

    public function __construct(
        public array $merchant_ids,
        public array $template_ids,
        public ?string $special_offer,
        public ?int $event_id,
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
            self::parseArray($data['merchant_ids']),
            self::parseArray($data['template_ids']),
            self::parseNullableString($data['special_offer']),
            self::parseNullableInt($data['event_id']),
            self::parseBool($data['post_merchant']),
            self::parseBool($data['post_alifshop']),
            $data['started_at'] ? Carbon::parse($data['started_at']) : null,
            $data['finished_at'] ? Carbon::parse($data['finished_at']) : null
        );
    }
}
