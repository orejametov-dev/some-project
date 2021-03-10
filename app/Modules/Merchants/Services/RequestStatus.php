<?php


namespace App\Modules\Merchants\Services;


class RequestStatus
{
    public const NEW = 1;
    public const ALLOWED = 2;
    public const TRASH = 3;
    public const IN_PROCESS = 4;

    private static $statuses = [
        self::NEW => [
            'id' => self::NEW,
            'name' => 'новый',
        ],
        self::ALLOWED => [
            'id' => self::ALLOWED,
            'name' => 'Одобрено',
        ],
        self::TRASH => [
            'id' => self::TRASH,
            'name' => 'В корзине',
        ],
        self::IN_PROCESS => [
            'id' => self::IN_PROCESS,
            'name' => 'На переговорах',
        ]
    ];

    public static function getOneById(int $id)
    {
        return json_decode(json_encode(self::$statuses[$id]));
    }

    public static function statusLists(): array
    {
        return [
            array('id' => self::NEW, 'name' => 'Новый'),
            array('id' => self::IN_PROCESS, 'name' => 'На переговорах'),
            array('id' => self::ALLOWED, 'name' => 'Одобрено'),
            array('id' => self::TRASH, 'name' => 'В корзине')
        ];
    }
}
