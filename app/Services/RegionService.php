<?php

namespace App\Services;

class RegionService
{
    private static $regions = [
        'tashkent_city' => [
            'body_ru' => 'Ташкент',
            'body_uz' => 'Toshkent '
        ],
        'andijan' => [
            'body_ru' => 'Андижанская область',
            'body_uz' => 'Andijon viloyati'
        ],
        'bukhara' => [
            'body_ru' => 'Бухарская область',
            'body_uz' => 'Buxoro viloyati'
        ],
        'jizzakh' => [
            'body_ru' => 'Джизакская область',
            'body_uz' => 'Jizzax viloyati'
        ],
        'qashqadaryo' => [
            'body_ru' => 'Кашкадарьинская область',
            'body_uz' => 'Qashqadaryo viloyati'
        ],
        'navoiy' => [
            'body_ru' => 'Навоийская область',
            'body_uz' => 'Navoiy viloyati'
        ],
        'namangan' => [
            'body_ru' => 'Наманганская область',
            'body_uz' => 'Namangan viloyati'
        ],
        'samarkand' => [
            'body_ru' => 'Самаркандская область',
            'body_uz' => 'Samarqand viloyati'
        ],
        'surxondaryo' => [
            'body_ru' => 'Сурхандарьинская область',
            'body_uz' => 'Surxondaryo viloyati'
        ],
        'sirdarya' => [
            'body_ru' => 'Сырдарьинская область',
            'body_uz' => 'Sirdaryo viloyati'
        ],
        'tashkent' => [
            'body_ru' => 'Ташкентская область',
            'body_uz' => 'Toshkent viloyati'
        ],
        'fergana' => [
            'body_ru' => 'Ферганская область',
            'body_uz' => 'Fargʻona viloyati'
        ],
        'xorazm' => [
            'body_ru' => 'Хорезмская область',
            'body_uz' => 'Xorazm viloyati'
        ],
        'karakalpakstan' => [
            'body_ru' => 'Республика Каракалпакстан',
            'body_uz' => 'Qoraqalpogʻiston Respublikasi'
        ]
    ];

    public static function getRegions(): array
    {
        return self::$regions;
    }

    public static function getKeys(): array
    {
        return array_keys(self::$regions);
    }
}
