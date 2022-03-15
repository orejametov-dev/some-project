<?php

declare(strict_types=1);

namespace App\HttpRepositories\HttpResponses\Core;

use Alifuz\Utils\Parser\ParseDataTrait;

class AmountOfMerchantSalesResponse
{
    use ParseDataTrait;

    public function __construct(
        public array $array,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            self::parseArray($data),
        );
    }
}
