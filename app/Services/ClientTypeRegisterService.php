<?php

namespace App\Services;

use App\Exceptions\BusinessException;

class ClientTypeRegisterService
{
    private static $client_type_register = [
        'MYID',
        'COMMON'
    ];

    public static function getClientTypeRegister(): array
    {
        return self::$client_type_register;
    }

    public static function getOneByKey(string $type)
    {
        if (!in_array($type, self::$client_type_register)) {
            throw new BusinessException('Указан не правильный тип регистрации', 400);
        }
    }
}
