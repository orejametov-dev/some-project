<?php

declare(strict_types=1);

namespace App\DTOs\Complaints;

use Alifuz\Utils\Parser\ParseDataTrait;

class StoreComplaintDTO
{
    use ParseDataTrait;

    public function __construct(
        public int $user_id,
        public array $meta
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            self::parseInt($data['user_id']),
            self::parseMeta($data['meta'])
        );
    }

    private static function parseMeta(array $meta): array
    {
        $parse_meta = [];
        $parse_meta['client_id'] = self::parseInt($meta['client_id']);
        $parse_meta['client_name'] = self::parseString($meta['client_name']);
        $parse_meta['client_surname'] = self::parseString($meta['client_surname']);
        $parse_meta['client_patronymic'] = self::parseString($meta['client_patronymic']);
        $parse_meta['reason_correction'] = self::parseArray($meta['reason_correction']);

        return $parse_meta;
    }
}
