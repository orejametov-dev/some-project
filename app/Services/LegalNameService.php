<?php

namespace App\Services;

class LegalNameService
{
    private static $legal_name_prefixes = [
        'LLC' => [
            'body_uz' => [
                'value' => 'MCHJ',
                'description' => 'Masuliyati Cheklangan Jamiyat'
           ],
            'body_ru' => [
                'value' => 'ООО',
                'description' => 'Общество с Ограниченной Ответственностью'
            ]
        ],
        'IE' => [
            'body_uz' => [
                'value' => 'YaTT',
                'description' => 'Yakka Tartibdagi Tadbirkor'
            ],
            'body_ru' => [
                'value' => 'ИП',
                'description' => 'Индивидуальный Предприниматель'
            ]
        ],
        'FE' => [
            'body_uz' => [
                'value' => 'XK',
                'description' => 'Xorijiy Korxona'
            ],
            'body_ru' => [
                'value' => 'ИП',
                'description' => 'Иностранное Преприятие'
            ]
        ],
        'UE' => [
            'body_uz' => [
                'value' => 'UK',
                'description' => 'Unitar Karxona',
            ],
            'body_ru' => [
                'value' => 'УП',
                'description' => 'Унитарная Предприятия'
            ]
        ],
        'GUE' => [
            'body_uz' => [
                'value' => 'DUK',
                'description' => 'Davlat Unitar Korxonasi'
            ],
            'body_ru' => [
                'value' => 'ГУП',
                'description' => 'Государственное Унитарное Предприятие'
            ]
        ],
        'SP' => [
            'body_uz' => [
                'value' => 'XK',
                'description' => 'Xususiy Korxona'
            ],
            'body_ru' => [
                'value' => 'ЧП',
                'description' => 'Частное Предприятие'
            ]
        ],

    ];

    public static function getNamePrefixes(): array
    {
        return self::$legal_name_prefixes;
    }


}
