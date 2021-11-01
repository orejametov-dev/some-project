<?php

namespace App\Services;

use App\Exceptions\BusinessException;

class ClientTypeRegisterService
{

    public const MYID = 'MYID';
    public const COMMON = 'COMMON';

    private static $client_type_register = [
        self::MYID => [
            'key' => self::MYID,
            'type' => 'MYID'
        ],
        self::COMMON => [
            'key' => self::COMMON,
            'type' => 'Регистрация'
        ]
    ];

    public static function getClientTypeRegister(): array
    {
        return self::$client_type_register;
    }

    public static function getOneByKey(string $type)
    {
        $type_register = array_search($type, array_column(self::$client_type_register, 'key', 'key'));

        if (!$type_register) {
            throw new BusinessException('Указан не правильный тип регистрации', 'type_not_exists');
        }

        return self::$client_type_register[$type_register];
    }
}
