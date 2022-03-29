<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * @method static self ALL()
 * @method static self CERTAIN()
 */
final class NotificationTypeEnum extends CastableEnum
{
    private const ALL = 'ALL';
    private const CERTAIN = 'CERTAIN';
}
