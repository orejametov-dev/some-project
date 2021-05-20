<?php


namespace App\Modules\Merchants\Services;


class MerchantStatus
{
    public const ACTIVE = 1;
    public const ARCHIVE = 2;
    public const BLOCK = 3;

    private static $statuses = [
        self::ACTIVE => [
            'id' => self::ACTIVE,
            'key' => 'ACTIVE',
            'name' => 'активный'
        ],
        self::ARCHIVE => [
            'id' => self::ARCHIVE,
            'key' => 'ARCHIVE',
            'name' => 'архивированный'
        ],
        self::BLOCK => [
            'id' => self::BLOCK,
            'key' => 'BLOCK',
            'name' => 'блокированный'
        ]
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
