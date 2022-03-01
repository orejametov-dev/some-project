<?php

declare(strict_types=1);

namespace App\DTOs\Merchants;

use Alifuz\Utils\Parser\ParseDataTrait;

class UpdateMerchantDTO
{
    use ParseDataTrait;

    public function __construct(
        public int $id,
        public string $name,
        public ?string $legal_name,
        public ?string $legal_name_prefix,
        public string $token,
        public string $alifshop_slug,
        public ?string $information,
        public int $min_application_price
    ) {
    }

    public static function fromArray(int $id, array $data): self
    {
        return new self(
            self::parseInt($id),
            self::parseString($data['name']),
            self::parseString($data['legal_name']),
            self::parseString($data['legal_name_prefix']),
            self::parseString($data['token']),
            self::parseString($data['alifshop_slug']),
            self::parseNullableString($data['information']),
            self::parseInt($data['min_application_price'])
        );
    }
}
