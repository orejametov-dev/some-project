<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * @method static self BEFORE()
 * @method static self AFTER()
 */
final class ProblemCaseTagTypeEnum extends CastableEnum
{
    private const BEFORE = 1;
    private const AFTER = 2;
}
