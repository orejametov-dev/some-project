<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * @method static self STRING_CODE_NOT_FOUND()
 * @method static self STRING_CODE_BAD_REQUEST()
 * @method static self STRING_CODE_UNAUTHENTICATED()
 */
final class ExceptionEnum extends CastableEnum
{
    private const STRING_CODE_NOT_FOUND = 'object_not_found';
    private const STRING_CODE_BAD_REQUEST = 'bad_request';
    private const STRING_CODE_UNAUTHENTICATED = 'unauthenticated';
}
