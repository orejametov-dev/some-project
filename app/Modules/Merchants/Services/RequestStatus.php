<?php


namespace App\Modules\Merchants\Services;


class RequestStatus
{
    public const NEW = 1;
    public const ALLOWED = 2;
    public const TRASH = 3;
    public const IN_PROCESS = 4;

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
