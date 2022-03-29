<?php

declare(strict_types=1);

namespace App\DTOs\Conditions;

use Alifuz\Utils\Entities\AbstractEntity;

final class UpdateConditionDTO extends AbstractEntity
{
    /**
     * @param int[] $store_ids
     */
    public function __construct(
        private array $store_ids,
        private int $duration,
        private int $commission,
        private ?string $special_offer,
        private ?int $event_id,
        private int $discount,
    ) {
    }

    /**
     * @return int[]
     */
    public function getStoreIds(): array
    {
        return $this->store_ids;
    }

    /**
     * @return int
     */
    public function getDuration(): int
    {
        return $this->duration;
    }

    /**
     * @return int
     */
    public function getCommission(): int
    {
        return $this->commission;
    }

    /**
     * @return string|null
     */
    public function getSpecialOffer(): ?string
    {
        return $this->special_offer;
    }

    /**
     * @return int|null
     */
    public function getEventId(): ?int
    {
        return $this->event_id;
    }

    /**
     * @return int
     */
    public function getDiscount(): int
    {
        return $this->discount;
    }

    public static function fromArray(array $data): static
    {
        return new static(
            store_ids: self::parseArray($data['store_ids'], defaultValue: []),
            duration: self::parseInt($data['duration'], defaultValue: 0),
            commission:  self::parseInt($data['commission']),
            special_offer:  self::parseNullableString($data['special_offer']),
            event_id:  self::parseNullableInt($data['event_id']),
            discount:  self::parseInt($data['discount'])
        );
    }

    public function jsonSerialize()
    {
        return [
            'store_ids' => $this->store_ids,
            'duration' => $this->duration,
            'commission' => $this->commission,
            'special_offer' => $this->special_offer,
            'event_id' => $this->event_id,
            'discount' => $this->discount,
        ];
    }
}
