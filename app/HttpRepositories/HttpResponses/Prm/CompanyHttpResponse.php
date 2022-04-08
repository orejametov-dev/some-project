<?php

declare(strict_types=1);

namespace App\HttpRepositories\HttpResponses\Prm;

use Alifuz\Utils\Parser\ParseDataTrait;
use App\HttpRepositories\HttpResponses\AbstractHttpResponse;

class CompanyHttpResponse extends AbstractHttpResponse
{
    use ParseDataTrait;

    public function __construct(
        public int $id,
        public string $name,
        public string $token,
        public string $legal_name,
        public string $legal_name_prefix,
        public string $module_alifshop,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            self::parseInt($data['id']),
            self::parseString($data['name']),
            self::parseString($data['token']),
            self::parseString($data['legal_name']),
            self::parseString($data['legal_name_prefix']),
            self::parseString($data['module_alifshop'])
        );
    }
}
