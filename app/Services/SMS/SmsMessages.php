<?php

namespace App\Services\SMS;

class SmsMessages
{
    public static function onAuthentication($code): string
    {
        $russian_text = "Prodiktuyte kod menejeru:  $code";
        $uzbek_text = ":Menejerga kodni ayting: $code";
        return $russian_text . PHP_EOL . $uzbek_text;
    }
}
