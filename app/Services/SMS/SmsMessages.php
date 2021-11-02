<?php

namespace App\Services\SMS;

class SmsMessages
{
    public static function onAuthentication($code): string
    {
        $russian_text = "Alifshop.uz: Dlya vashey bezopasnosti nikomu ne pokazyvayte kod. $code";
        $uzbek_text = "Xavfsizligingiz uchun kodni hech kimga ko'rsatmang. $code";
        return $russian_text . PHP_EOL . $uzbek_text;
    }

    public static function onCbAgreement($code): string
    {
        $russian_text = "Alifshop.uz: Vnosya danniy kod vy daete svoyo soglasie na poluchenie kreditnogo otcheta o sebe, kreditnoy organizatsiey OOO Alif Moliya - $code";
        $uzbek_text = "Ushbu kodni kiritib, o'zingiz, OOO Alif Moliya kredit muassasasi tomonidan kredit hisobotingizni olishga rozilik bildirasiz - $code";
        return $russian_text . PHP_EOL . $uzbek_text;
    }

    public static function onRegisteringClient($code): string
    {
        $russian_text = "Alifshop.uz: Vi proxodite registratsiyu dlya polucheniya rassrochki. Esli eto ne vi, napishite nam v https://t.me/alifshopuzbot Nikomu ne pokazivayte kod bezopasnosti $code. Oznakomit'sa s ofertoy mojete zdes'";
        $uzbek_text = "Siz muddatli to'lovga mahsulot olish uchun ro'yxatdan o'tmoqdasiz. Agar bu siz bo'lmasangiz, bizga https://t.me/alifshopuzbot ga yozing. Xavfsizlik kodini hech kimga ko'rsatmang $code. Ofertani berilgan havola orqali ko'rishingiz mumkin https://alifshop.uz/terms.";
        return $russian_text . PHP_EOL . $uzbek_text;
    }

    public static function onCreatingCredit($code): string
    {
        return 'Alifshop.uz: Kod podtverjdeniya ' . $code . ' Ispolzuya etot kod vy soglashaetes s usloviyami publichnoy offerty: https://alifshop.uz/terms';
    }

    // online
    public static function becomeAzo($language = 'ru'): string
    {
        $russian = "Pozdravlyaem, teper' vi nash A'zo :) Kupit' tovar mojete na https://alifshop.uz/";
        $uzbek = "Tabriklaymiz, endi siz bizning A'zoyimizsiz :) https://alifshop.uz/ saytida mahsulot xarid qilsangiz bo’ladi";
        if ($language == 'uz')
            return $uzbek;
        else if ($language == 'ru')
            return $russian;
        return $russian . PHP_EOL . $uzbek;
    }

    public static function becomeFixing(): string
    {
        $russian = "Vy nepravilno zapolnili dannye, pozjaluysta proydite po https://online.alifshop.uz/ dlya polucheniya podrobnoy informatsii";
        $uzbek = "Siz noto'g'ri ma'lumot kiritgansiz, iltimos batafsil ma'lumot uchun berilgan havolaga kiring https://online.alifshop.uz/";
        return $russian . PHP_EOL . $uzbek;
    }

    // online zayavka
    public static function onApplicationCreate($id): string
    {
        $russian = "Ura, mi jdali zayavku №$id. Seychas je rassmotrim :) Skoro poluchite sms s otvetom https://t.me/alifshopuzbot";
        $uzbek = "Ure, sizni №$id raqamli arizangizni kutayotgan edik. Hoziroq ko'rib chiqamiz :) Tez orada javobini sms tarzida olasiz https://t.me/alifshopuzbot";
        return $russian . PHP_EOL . $uzbek;
    }

    public static function onApplicationRejected($id): string
    {
        $russian = "Ochen jal', vam otkazano v rassrochke №$id. Napishite v https://t.me/alifshopuzbot, vozmojno smojem chem-to pomoch";
        $uzbek = "Afsus, arizangiz №$id rad etildi. Savollaringiz bo'lsa t.me/alifazo yozing, va umid qilamiz ki sizga yordam bera olamiz";
        return $russian . PHP_EOL . $uzbek;
    }

    public static function onApplicationApproved($id, $store_name, $store_phone): string
    {
        $russian = "Yeeeee, vasha zayavka №$id odobrena. Skoro magazin $store_name $store_phone svyajetsa s vami";
        $uzbek = "Yeeeee, №$id raqamli arizangizga rozilik berildi. Tez oraqa $store_name $store_phone siz bilan bog'lanadi";
        return $russian . PHP_EOL . $uzbek;
    }

    public static function onApplicationCancelled($id): string
    {
        $russian = "Vasha zayavka №$id otmenena. Budem jdat vas eshe :)";
        $uzbek = "Sizning №$id raqamli arizangiz bekor qilindi. Sizni yana kutamiz :)";
        return $russian . PHP_EOL . $uzbek;
    }
}
