<?php


namespace App\Modules\Merchants\Services;


class ProblemCaseStatus
{
    public const NEW = 1;
    public const IN_PROCESS = 2;
    public const DONE = 3;
    public const FINISHED = 4;

    public static $statuses = [
        self::NEW => [
            'id' => self::NEW,
            'name' => 'Новый',
            'lang' => [
                'uz' => 'Yangi',
                'ru' => 'Новый'
            ]
        ],
        self::IN_PROCESS => [
            'id' => self::IN_PROCESS,
            'name' => 'В процессе',
            'lang' => [
                'uz' => 'Ko\'rib chiqilmoqda',
                'ru' => 'В процессе'
            ]
        ],
        self::DONE => [
            'id' => self::DONE,
            'name' => 'Выполнено',
            'lang' => [
                'uz' => 'Bajarildi',
                'ru' => 'Выполнено'
            ]
        ],
        self::FINISHED => [
            'id' => self::FINISHED,
            'name' => 'Завершен',
            'lang' => [
                'uz' => 'Tugatildi',
                'ru' => 'Завершен'
            ]
        ]
    ];

    public static function getOneById(int $id)
    {
        return json_decode(json_encode(self::$statuses[$id]));
    }

    public static function statusLists(): array
    {
        return [
            array('id' => self::NEW, 'name' => 'Новый'),
            array('id' => self::IN_PROCESS, 'name' => 'В процесс'),
            array('id' => self::DONE, 'name' => 'Выполнено'),
            array('id' => self::FINISHED, 'name' => 'Завершен')
        ];
    }
}
