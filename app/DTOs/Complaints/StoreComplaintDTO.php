<?php

declare(strict_types=1);

namespace App\DTOs\Complaints;

use Alifuz\Utils\Parser\ParseDataTrait;

class StoreComplaintDTO
{
    use ParseDataTrait;

    public function __construct(
        public int    $user_id,
        public string $reason_correction,
        public array  $meta
    )
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            self::parseInt($data['user_id']),
            self::parseString($data['reason_correction']),
            self::parseMeta($data['meta'])
        );
    }

    private static function parseMeta(array $meta): array
    {
        $parse_meta = [];
        $parse_meta['id'] = self::parseInt($meta['id']);
        $parse_meta['name'] = self::parseString($meta['name']);
        $parse_meta['surname'] = self::parseString($meta['surname']);
        $parse_meta['patronymic'] = self::parseString($meta['patronymic']);

        return $parse_meta;
    }
}
