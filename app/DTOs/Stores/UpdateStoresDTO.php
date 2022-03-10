<?php

declare(strict_types=1);

namespace App\DTOs\Stores;

use Alifuz\Utils\Parser\ParseDataTrait;

class UpdateStoresDTO
{
    use ParseDataTrait;

    public function __construct(
        public int $store_id,
        public string $name,
        public ?string $phone,
        public ?string $address,
        public ?string $responsible_person,
        public ?string $responsible_person_phone,
        public string $region,
        public ?string $district,
        public ?float $lat,
        public ?float $long
    ) {
    }

    public static function fromArray(int $store_id, array $data): self
    {
        return new self(
            self::parseInt($store_id),
            self::parseString($data['name']),
            self::parseNullableString($data['phone']),
            self::parseNullableString($data['address']),
            self::parseNullableString($data['responsible_person']),
            self::parseNullableString($data['responsible_person_phone']),
            self::parseString($data['region']),
            self::parseNullableString($data['district']),
            self::parseNullableFloat($data['lat']),
            self::parseNullableFloat($data['long'])
        );
    }
}
