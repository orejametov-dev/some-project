<?php

declare(strict_types=1);

namespace App\DTOs\Notifications;

use Alifuz\Utils\Entities\AbstractEntity;

final class NotificationRecipientDTO extends AbstractEntity
{
    /**
     * @param int $merchant_id
     * @param array<int> $store_ids
     */
    public function __construct(
        private int $merchant_id,
        private ?array $store_ids = null
    ) {
    }

    /**
     * @return int
     */
    public function getMerchantId(): int
    {
        return $this->merchant_id;
    }

    /**
     * @return array<int>
     */
    public function getStoreIds(): array
    {
        return $this->store_ids;
    }

    /**
     * @param array<mixed> $data
     */
    public static function fromArray(array $data): static
    {
        return new static(
            merchant_id: self::parseInt($data['merchant_id']),
            store_ids: self::parseNullableArray($data['store_ids'])
        );
    }

    /**
     * @return mixed[]
     */
    public function jsonSerialize()
    {
        return [
            'merchant_id' => $this->merchant_id,
            'store_ids' => $this->store_ids,
        ];
    }
}
