<?php

namespace App\HttpRepositories\HttpResponses\Prm;

use Alifuz\Utils\Parser\ParseDataTrait;
use App\HttpRepositories\HttpResponses\AbstractHttpResponse;

class CompanyUserHttpResponse extends AbstractHttpResponse
{
    use ParseDataTrait;

    public function __construct(
        public int $id,
        public int $user_id,
        public int $company_id,
        public string $name,
        public string $phone
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            self::parseInt($data['id']),
            self::parseInt($data['user_id']),
            self::parseInt($data['company_id']),
            self::parseString($data['full_name']),
            self::parseString($data['phone'])
        );
    }
}
