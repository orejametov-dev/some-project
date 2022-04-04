<?php

declare(strict_types=1);

namespace App\DTOs\Competitors;

use Alifuz\Utils\Entities\AbstractEntity;
use Carbon\Carbon;

final class SaveCompetitorDTO extends AbstractEntity
{
    public function __construct(
        private int $competitor_id,
        private ?int $volume_sales,
        private ?int $percentage_approve,
        private ?Carbon $partnership_at,
    ) {
    }

    /**
     * @return int
     */
    public function getCompetitorId(): int
    {
        return $this->competitor_id;
    }

    /**
     * @return int|null
     */
    public function getVolumeSales(): ?int
    {
        return $this->volume_sales;
    }

    /**
     * @return int|null
     */
    public function getPercentageApprove(): ?int
    {
        return $this->percentage_approve;
    }

    /**
     * @return Carbon|null
     */
    public function getPartnershipAt(): ?Carbon
    {
        return $this->partnership_at;
    }

    /**
     * @param array<mixed> $data
     */
    public static function fromArray(array $data): static
    {
        return new self(
            competitor_id: self::parseInt($data['competitor_id']),
            volume_sales: self::parseNullableInt($data['volume_sales']),
            percentage_approve: self::parseNullableInt($data['percentage_approve']),
            partnership_at: self::parseNullableCarbon($data['partnership_at']),
        );
    }

    /**
     * @return mixed[]
     */
    public function jsonSerialize()
    {
        return [
            'competitor_id' => $this->competitor_id,
            'volume_sales' => $this->volume_sales,
            'percentage_approve' => $this->percentage_approve,
            'partnership_at' => $this->partnership_at,
        ];
    }
}
