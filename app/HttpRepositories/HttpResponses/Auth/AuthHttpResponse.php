<?php

declare(strict_types=1);

namespace App\HttpRepositories\HttpResponses\Auth;

use Alifuz\Utils\Parser\ParseDataTrait;

class AuthHttpResponse
{
    use ParseDataTrait;

    public function __construct(
        public int $id,
        public string $name,
        public string $phone,
        public array $roles,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            self::parseInt($data['id']),
            self::parseString($data['name']),
            self::parseString($data['phone']),
            self::parseArray($data['roles']),
        );
    }
}
