<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * @method static self STRING_CODE_NOT_FOUND()
 * @method static self CODE_NOT_FOUND()
 * @method static self STRING_CODE_BAD_REQUEST()
 * @method static self CODE_BAD_REQUEST()
 */
final class ExceptionEnum extends CastableEnum
{
    private const STRING_CODE_NOT_FOUND = 'object_not_found';
    private const CODE_NOT_FOUND = 404;
    private const STRING_CODE_BAD_REQUEST = 'unauthenticated';
    private const CODE_BAD_REQUEST = 401;
}
