<?php

declare(strict_types=1);

namespace App\HttpRepositories\HttpResponses\Core;

use Alifuz\Utils\Parser\ParseDataTrait;

class MerchantApplicationsAndClientsCountByRangeDataResponse
{
    use ParseDataTrait;

    public function __construct(
        public int $applications_count,
        public int $clients_count
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            self::parseInt($data['applications_count']),
            self::parseInt($data['clients_count']),
        );
    }
}
