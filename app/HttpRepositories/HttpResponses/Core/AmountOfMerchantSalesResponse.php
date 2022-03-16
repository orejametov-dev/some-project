<?php

namespace App\HttpRepositories\HttpResponses\Core;

use Alifuz\Utils\Parser\ParseDataTrait;

class AmountOfMerchantSalesResponse
{
    use ParseDataTrait;

    public function __construct(
        private int $merchant_id,
        private int $discounted_amount,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            merchant_id: self::parseInt($data['merchant_id']),
            discounted_amount: self::parseInt($data['discounted_amount'])
        );
    }

    public function getMerchantId(): int
    {
        return $this->merchant_id;
    }

    public function getDiscountedAmount(): int
    {
        return $this->discounted_amount;
    }
}
