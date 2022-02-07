<?php

namespace App\HttpRepositories\HttpResponses\Auth;

use Alifuz\Utils\Parser\ParseDataTrait;

class AuthHttpResponse
{
    use ParseDataTrait;

    public function __construct(
        public int $id,
        public string $name,
        public string $phone
    ) {
    }

    public static function fromArray(array $data): self
    {
        $data = $data['data'];

        return new self(
            self::parseInt($data['id']),
            self::parseString($data['name']),
            self::parseString($data['phone'])
        );
    }
}
