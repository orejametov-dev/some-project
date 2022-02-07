<?php

declare(strict_types=1);

namespace App\DTOs\MerchantInfos;

use Alifuz\Utils\Parser\ParseDataTrait;

class StoreMerchantInfoDTO
{
    use ParseDataTrait;

    public function __construct(
        public int $merchant_id,
        public string $director_name,
        public string $legal_name,
        public string $legal_name_prefix,
        public string $phone,
        public string $vat_number,
        public string $mfo,
        public string $tin,
        public string $oked,
        public string $bank_account,
        public string $bank_name,
        public string $address,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            self::parseInt($data['merchant_id']),
            self::parseString($data['director_name']),
            self::parseString($data['legal_name']),
            self::parseString($data['legal_name_prefix']),
            self::parseString($data['phone']),
            self::parseString($data['vat_number']),
            self::parseString($data['mfo']),
            self::parseString($data['tin']),
            self::parseString($data['oked']),
            self::parseString($data['bank_account']),
            self::parseString($data['bank_name']),
            self::parseString($data['address'])
        );
    }
}
