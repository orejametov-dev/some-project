<?php

declare(strict_types=1);

namespace App\Services\Helpers;

class Randomizr
{
    public static function generateOtp() : int
    {
        return rand(1000, 9999);
    }
}
