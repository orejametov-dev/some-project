<?php

declare(strict_types=1);

namespace App\DTOs\Stores;

use Alifuz\Utils\Parser\ParseDataTrait;

class StoreStoresDTO
{
    use ParseDataTrait;

    public function __construct(
        public string  $name,
        public int     $merchant_id,
        public ?string $address,
        public ?string $responsible_person,
        public ?int    $responsible_person_phone,
        public string  $region,
        public string  $district
    )
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            self::parseString($data['name']),
            self::parseInt($data['merchant_id']),
            self::parseNullableString($data['address']),
            self::parseNullableString($data['responsible_person']),
            self::parseNullableInt($data['responsible_person_phone']),
            self::parseString($data['region']),
            self::parseString($data['district'])
        );
    }
}
