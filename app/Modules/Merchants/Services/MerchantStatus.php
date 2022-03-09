<?php

namespace App\Modules\Merchants\Services;

class MerchantStatus
{
    public const ACTIVE = 1;
    public const ARCHIVE = 2;

    private static array $statuses = [
        self::ACTIVE => [
            'id' => self::ACTIVE,
            'key' => 'ACTIVE',
            'name' => 'активный',
        ],
        self::ARCHIVE => [
            'id' => self::ARCHIVE,
            'key' => 'ARCHIVE',
            'name' => 'архивированный',
        ],
    ];

    public static function getOneById(int $id)
    {
        return json_decode(json_encode(self::$statuses[$id]));
    }

    public static function get(): array
    {
        return array_values(self::$statuses);
    }

    public static function keys(): array
    {
        return array_keys(self::$statuses);
    }
}
