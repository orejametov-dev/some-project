<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * @method static self NEW()
 * @method static self ALLOWED()
 * @method static self TRASH()
 * @method static self IN_PROCESS()
 * @method static self ON_TRAINING()
 */
final class MerchantRequestStatusEnum extends CastableEnum
{
    private const NEW = 1;
    private const ALLOWED = 2;
    private const TRASH = 3;
    private const IN_PROCESS = 4;
    private const ON_TRAINING = 5;
}
