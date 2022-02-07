<?php

namespace App\Services\Helpers;

class Randomizr
{
    public static function generateOtp()
    {
        return rand(1000, 9999);
    }
}
