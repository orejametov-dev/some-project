<?php

declare(strict_types=1);

namespace App\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static self OTP()
 * @method static self LOGS()
 */
final class CacheTypeEnum extends Enum
{
    private const OTP = 'otp';
    private const LOGS = 'logs';
}
