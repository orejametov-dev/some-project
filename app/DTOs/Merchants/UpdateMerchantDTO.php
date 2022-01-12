<?php


namespace App\DTOs\Merchants;


class UpdateMerchantDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $legal_name,
        public ?string $legal_name_prefix,
        public string $token,
        public string $alifshop_slug,
        public ?string $information,
        public int $min_application_price
    )
    {
    }
}
