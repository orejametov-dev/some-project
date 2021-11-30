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

    public static function onFinishedProblemCases($name , $problem_case_id)
    {
        $russian_text = "Assalomu alaykum, $name, vasha zayavka pod nomerom $problem_case_id vipolnena. Mi nadeyemsya, chto nasha komanda smogla vam pomoch.Yesli u vas yest dopolnitelniye voprosi ili jalobi po povodu resheniya problemi, soobshite nam ob etom po tel.nomeru 71 202 04 99 ili https://t.me/alifazo.";
        $uzbek_text = "Assalomu alaykum, $name, sizning $problem_case_id  raqamli murojaatingiz o'z nihoyasiga yetkazildi.Jamoamiz sizga yordam bera oldi degan umiddamiz, qo'shimcha savollaringiz yoki muammo hal etilmaganligi haqida e'tirozingiz bo'lsa, 71 202 04 99 tel.raqami yoki https://t.me/alifazo ga xabar bering.";
        return $russian_text . PHP_EOL . $uzbek_text;
    }

    public static function onNewProblemCases($name , $problem_case_id)
    {
        $russian_text = "Assalomu alaykum, $name, sizning $problem_case_id raqamli murojaatingiz qabul qilindi. Tez orada sizga muammoning yechimi bilan qaytamiz.";
        $uzbek_text = "Assalomu alaykum, $name, vasha zayavka pod nomerom $problem_case_id prinyata. Mi skoro svyajemsya s vami s resheniyem problemi.";
        return $russian_text . PHP_EOL . $uzbek_text;
    }
}
