<?php

declare(strict_types=1);

namespace App\DTOs\Competitors;

use Alifuz\Utils\Parser\ParseDataTrait;
use Carbon\Carbon;

class CompetitorDTO
{
    use ParseDataTrait;

    /**
     * @param int $merchant_id
     * @param int $competitor_id
     * @param int|null $volume_sales
     * @param int|null $percentage_approve
     * @param Carbon|null $partnership_at
     */
    public function __construct(
        public int $merchant_id,
        public int $competitor_id,
        public ?int $volume_sales,
        public ?int $percentage_approve,
        public ?Carbon $partnership_at,
    ) {
    }

    public static function fromArray(int $merchant_id, array $data): self
    {
        return new self(
            self::parseInt($merchant_id),
            self::parseInt($data['competitor_id']),
            self::parseNullableInt($data['volume_sales']),
            self::parseNullableInt($data['percentage_approve']),
            isset($data['partnership_at']) ? Carbon::parse($data['partnership_at']) : null,
        );
    }
}
