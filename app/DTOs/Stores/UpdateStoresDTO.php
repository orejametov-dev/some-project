<?php

declare(strict_types=1);

namespace App\DTOs\Stores;

use Alifuz\Utils\Entities\AbstractEntity;

final class UpdateStoresDTO extends AbstractEntity
{
    public function __construct(
        private string $name,
        private ?string $phone,
        private ?string $address,
        private ?string $responsible_person,
        private ?string $responsible_person_phone,
        private string $region,
        private ?string $district,
        private ?float $lat,
        private ?float $long
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
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
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
     * @return string|null
     */
    public function getDistrict(): ?string
    {
        return $this->district;
    }

    /**
     * @return float|null
     */
    public function getLat(): ?float
    {
        return $this->lat;
    }

    /**
     * @return float|null
     */
    public function getLong(): ?float
    {
        return $this->long;
    }

    /**
     * @param array<mixed> $data
     */
    public static function fromArray(array $data): static
    {
        return new static(
            name: self::parseString($data['name']),
            phone: self::parseNullableString($data['phone']),
            address: self::parseNullableString($data['address']),
            responsible_person:  self::parseNullableString($data['responsible_person']),
            responsible_person_phone:  self::parseNullableString($data['responsible_person_phone']),
            region:  self::parseString($data['region']),
            district:  self::parseNullableString($data['district']),
            lat:  self::parseNullableFloat($data['lat']),
            long:  self::parseNullableFloat($data['long'])
        );
    }

    /**
     * @return mixed[]
     */
    public function jsonSerialize()
    {
        return [
            'name' => $this->name,
            'phone' => $this->phone,
            'address' => $this->address,
            'responsible_person' => $this->responsible_person,
            'responsible_person_phone' => $this->responsible_person_phone,
            'region' => $this->region,
            'district' => $this->district,
            'lat' => $this->lat,
            'long' => $this->long,
        ];
    }
}
