<?php

declare(strict_types=1);

namespace App\DTOs\Stores;

use Alifuz\Utils\Entities\AbstractEntity;

final class StoreStoresDTO extends AbstractEntity
{
    public function __construct(
        private string $name,
        private int $merchant_id,
        private ?string $address,
        private ?string $responsible_person,
        private ?string $responsible_person_phone,
        private string $region,
        private string $district
    ) {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getMerchantId(): int
    {
        return $this->merchant_id;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @return string|null
     */
    public function getResponsiblePerson(): ?string
    {
        return $this->responsible_person;
    }

    /**
     * @return string|null
     */
    public function getResponsiblePersonPhone(): ?string
    {
        return $this->responsible_person_phone;
    }

    /**
     * @return string
     */
    public function getRegion(): string
    {
        return $this->region;
    }

    /**
     * @return string
     */
    public function getDistrict(): string
    {
        return $this->district;
    }

    /**
     * @param array<mixed> $data
     */
    public static function fromArray(array $data): static
    {
        return new static(
            name: self::parseString($data['name']),
            merchant_id: self::parseInt($data['merchant_id']),
            address: self::parseNullableString($data['address']),
            responsible_person:  self::parseNullableString($data['responsible_person']),
            responsible_person_phone:  self::parseNullableString($data['responsible_person_phone']),
            region:  self::parseString($data['region']),
            district:  self::parseString($data['district'])
        );
    }

    /**
     * @return mixed[]
     */
    public function jsonSerialize()
    {
        return [
            'name' => $this->name,
            'merchant_id' => $this->merchant_id,
            'address' => $this->address,
            'responsible_person' => $this->responsible_person,
            'responsible_person_phone' => $this->responsible_person_phone,
            'region' => $this->region,
            'district' => $this->district,
        ];
    }
}
