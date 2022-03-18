<?php

declare(strict_types=1);

namespace App\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static self NEW()
 * @method static self IN_PROCESS()
 * @method static self DONE()
 * @method static self FINISHED()
 */
final class ProblemCaseStatusEnum extends Enum
{
    private const NEW = 1;
    private const IN_PROCESS = 2;
    private const DONE = 3;
    private const FINISHED = 4;
}
