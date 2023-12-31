<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MerchantRequestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $merchant_request_1 = [
            'id' => 1,
            'name' => 'Mediapark',
            'legal_name' => 'OOO Mediapark',
            'user_name' => 'Jhon Artikkhuja',
            'user_phone' => '998901234050',
            'status_id' => 1,
            'region' => 'Samarqand viloyati',
            'engaged_by_id' => 1,
            'engaged_at' => '2021-05-14 10:00:00',
            'status_updated_at' => null,
            'categories' => json_encode(['Бытовая техника', 'Смарфон']),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $merchant_request_2 = [
            'id' => 2,
            'name' => 'Artel',
            'legal_name' => 'OAO Artel',
            'user_name' => 'Jakhongir Artikkhujaev',
            'user_phone' => '998912345060',
            'status_id' => 4,
            'region' => 'Surxondaryo viloyati',
            'engaged_by_id' => 2,
            'engaged_at' => '2021-05-14 11:00:01',
            'status_updated_at' => '2021-05-14 11:00:01',
            'categories' => json_encode(['Техника', 'Смарфон']),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $merchant_request_3 = [
            'id' => 3,
            'name' => 'Miniso',
            'legal_name' => 'OOO Miniso',
            'user_name' => 'Artikkhuja Jahongirov',
            'user_phone' => '998923456070',
            'status_id' => 3,
            'region' => 'Qashqadaryo viloyati',
            'engaged_by_id' => 3,
            'engaged_at' => '2021-05-14 12:00:02',
            'status_updated_at' => '2021-05-14 12:00:02',
            'categories' => json_encode(['Бытовая техника', 'Мебель']),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $merchant_request_4 = [
            'id' => 4,
            'name' => 'GSHOP',
            'legal_name' => 'OOO GSHOP',
            'user_name' => 'Vasya Pupkin',
            'user_phone' => '998934440010',
            'status_id' => 2,
            'region' => 'Toshkent shahri',
            'engaged_by_id' => 4,
            'engaged_at' => '2021-05-14 13:00:03',
            'status_updated_at' => '2021-05-14 13:00:03',
            'categories' => json_encode(['Бытовая техника', 'Смарфон']),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        DB::table('merchant_requests')->insert([
            $merchant_request_1,
            $merchant_request_2,
            $merchant_request_3,
            $merchant_request_4,
        ]);
    }
}
