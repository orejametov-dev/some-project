<?php

declare(strict_types=1);

namespace App\DTOs\MerchantInfos;

use Alifuz\Utils\Parser\ParseDataTrait;

class UpdateMerchantInfoDTO
{
    use ParseDataTrait;

    public function __construct(
        public string $director_name,
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
            self::parseString($data['director_name']),
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
