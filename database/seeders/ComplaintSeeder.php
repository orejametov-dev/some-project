<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ComplaintSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $complaint_1 = [
            'azo_merchant_access_id' => 1,
            'meta' => json_encode([
                'client_id' => 1,
                'client_name' => 'Dmitriy',
                'client_surname' => 'Nemnogo',
                'client_patronymic' => 'Hellowich',
                'reason_correction' => 'Не правильно написанно имя',
            ]),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $complaint_2 = [
            'azo_merchant_access_id' => 2,
            'meta' => json_encode([
                'client_id' => 2,
                'client_name' => 'Oleg',
                'client_surname' => 'Nebo',
                'client_patronymic' => 'Krasivovoch',
                'reason_correction' => 'Не правильно написанно имя',
            ]),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $complaint_3 = [
            'azo_merchant_access_id' => 3,
            'meta' => json_encode([
                'client_id' => 3,
                'client_name' => 'Nikita',
                'client_surname' => 'Bezrabotniy',
                'client_patronymic' => 'Nelepovich',
                'reason_correction' => 'Не правильно написанно имя',
            ]),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $complaint_4 = [
            'azo_merchant_access_id' => 4,
            'meta' => json_encode([
                'client_id' => 4,
                'client_name' => 'Hamauyn',
                'client_surname' => 'Oltinbesh',
                'client_patronymic' => 'Al oqli',
                'reason_correction' => 'Не правильно написанно имя',
            ]),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $complaint_5 = [
            'azo_merchant_access_id' => 5,
            'meta' => json_encode([
                'client_id' => 5,
                'client_name' => 'Alloha',
                'client_surname' => 'Luchshiy',
                'client_patronymic' => 'Igrokovich',
                'reason_correction' => 'Не правильно написанно имя',
            ]),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $complaint_6 = [
            'azo_merchant_access_id' => 6,
            'meta' => json_encode([
                'client_id' => 6,
                'client_name' => 'Lol',
                'client_surname' => 'Kekov',
                'client_patronymic' => 'Kekovich',
                'reason_correction' => 'Не правильно написанно имя',
            ]),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $complaint_7 = [
            'azo_merchant_access_id' => 7,
            'meta' => json_encode([
                'client_id' => 7,
                'client_name' => 'Rollton',
                'client_surname' => 'Pokupav',
                'client_patronymic' => 'Vigodovich',
                'reason_correction' => 'Не правильно написанно имя',
            ]),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $complaint_8 = [
            'azo_merchant_access_id' => 1,
            'meta' => json_encode([
                'client_id' => 1,
                'client_name' => 'Dmitriy',
                'client_surname' => 'Nemnogo',
                'client_patronymic' => 'Hellowich',
                'reason_correction' => 'Не правильно добавил фото',
            ]),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $complaint_9 = [
            'azo_merchant_access_id' => 1,
            'meta' => json_encode([
                'client_id' => 2,
                'client_name' => 'Chto',
                'client_surname' => 'Tam',
                'client_patronymic' => 'Chto',
                'reason_correction' => 'Не понравилось лицо',
            ]),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $complaint_10 = [
            'azo_merchant_access_id' => 2,
            'meta' => json_encode([
                'client_id' => 1,
                'client_name' => 'Dmitriy',
                'client_surname' => 'Nemnogo',
                'client_patronymic' => 'Hellowich',
                'reason_correction' => 'Не правильно написанно имя',
            ]),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        \DB::table('complaints')->insert([
            $complaint_1,
            $complaint_2,
            $complaint_3,
            $complaint_4,
            $complaint_5,
            $complaint_6,
            $complaint_7,
            $complaint_8,
            $complaint_9,
            $complaint_10
            ]);
    }
}
