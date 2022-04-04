<?php

declare(strict_types=1);

namespace App\DTOs\Auth;

use Alifuz\Utils\Entities\AbstractEntity;

final class AzoAccessDto extends AbstractEntity
{
    public function __construct(
        private int $merchant_id,
        private int $store_id,
        private int $id
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
     * @return int
     */
    public function getStoreId(): int
    {
        return $this->store_id;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param array<mixed> $data
     */
    public static function fromArray(array $data): static
    {
        return new static(
            merchant_id: self::parseInt($data['merchant_id']),
            store_id: self::parseInt($data['store_id']),
            id: self::parseInt($data['id'])
        );
    }

    /**
     * @return mixed[]
     */
    public function jsonSerialize()
    {
        return [
            'merchant_id' => $this->merchant_id,
            'store_id' => $this->store_id,
            'id' => $this->id,
        ];
    }
}
