<?php

namespace App\DTOs\MerchantRequest;

use Alifuz\Utils\Parser\ParseDataTrait;

class StoreMerchantRequestDTO
{
    use ParseDataTrait;

    public function __construct(
        public string $user_name,
        public string $user_phone,
        public string $name,
        public string $legal_name,
        public string $legal_name_prefix,
        public array $categories,
        public int $approximate_sales,
        public string $region,
        public string $district,
        public ?string $address = null,
    ) {
    }

    public static function fromArray(array $data)
    {
        return new self(
            self::parseString($data['user_name']),
            self::parseString($data['user_phone']),
            self::parseString($data['name']),
            self::parseString($data['legal_name']),
            self::parseString($data['legal_name_prefix']),
            self::parseArray($data['categories']),
            self::parseInt($data['approximate_sales']),
            self::parseString($data['region']),
            self::parseString($data['district']),
            isset($data['address']) ? self::parseString($data['address']) : null
        );
    }
}
