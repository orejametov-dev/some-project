<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanyUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $merchant_1_users = [
            'company_id' => 1,
            'user_id' => 1,
            'full_name' => 'Abror',
            'phone' => '998906554411',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];
        $merchant_2_users = [
            'company_id' => 1,
            'user_id' => 2,
            'full_name' => 'Axror',
            'phone' => '998906554478',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),

        ];

        $merchant_3_users = [
            'company_id' => 2,
            'user_id' => 3,
            'full_name' => 'Anton',
            'phone' => '998999895512',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];
        $merchant_4_users = [
            'company_id' => 2,
            'user_id' => 4,
            'full_name' => 'Dima',
            'phone' => '998917884545',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $merchant_5_users = [
            'company_id' => 3,
            'user_id' => 5,
            'full_name' => 'Anton',
            'phone' => '998914455445',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $merchant_6_users = [
            'company_id' => 3,
            'user_id' => 16,
            'full_name' => 'Shox',
            'phone' => '998906554422',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $merchant_7_users = [
            'company_id' => 5,
            'user_id' => 6,
            'full_name' => 'Shox2',
            'phone' => '998906553333',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $merchant_8_users = [
            'company_id' => 5,
            'user_id' => 7,
            'full_name' => 'Shox3',
            'phone' => '8898656521',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $merchant_9_users = [
            'company_id' => 6,
            'user_id' => 8,
            'full_name' => 'Dmitriy',
            'phone' => '8898656523',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $merchant_10_users = [
            'company_id' => 6,
            'user_id' => 9,
            'full_name' => 'Rodion',
            'phone' => '8898902221447',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $merchant_11_users = [
            'company_id' => 7,
            'user_id' => 10,
            'full_name' => 'Anotoliy',
            'phone' => '88986567781',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $merchant_12_users = [
            'company_id' => 7,
            'user_id' => 11,
            'full_name' => 'Anotoliy2',
            'phone' => '8898656524',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $merchant_13_users = [
            'company_id' => 8,
            'user_id' => 13,
            'full_name' => 'Alesya',
            'phone' => '8898656525',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $merchant_14_users = [
            'company_id' => 8,
            'user_id' => 14,
            'full_name' => 'Alesya2',
            'phone' => '8898656526',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $merchant_15_users = [
            'company_id' => 9,
            'user_id' => 17,
            'full_name' => 'Konstantin',
            'phone' => '8898656527',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $merchant_16_users = [
            'company_id' => 9,
            'user_id' => 18,
            'full_name' => 'Radj',
            'phone' => '8898656528',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $merchant_17_users = [
            'company_id' => 10,
            'user_id' => 19,
            'full_name' => 'Kontanten',
            'phone' => '8898656529',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $merchant_18_users = [
            'company_id' => 10,
            'user_id' => 20,
            'full_name' => 'Konstantin3',
            'phone' => '8898656600',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $merchant_19_users = [
            'company_id' => 4,
            'user_id' => 21,
            'full_name' => 'Radj2',
            'phone' => '889865661',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $merchant_20_users = [
            'company_id' => 4,
            'user_id' => 22,
            'full_name' => 'Gulchitay',
            'phone' => '8898656666',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        DB::table('company_users')->insert([
            $merchant_1_users,
            $merchant_2_users,
            $merchant_3_users,
            $merchant_4_users,
            $merchant_5_users,
            $merchant_6_users,
            $merchant_7_users,
            $merchant_8_users,
            $merchant_9_users,
            $merchant_10_users,
            $merchant_11_users,
            $merchant_12_users,
            $merchant_13_users,
            $merchant_14_users,
            $merchant_15_users,
            $merchant_16_users,
            $merchant_17_users,
            $merchant_18_users,
            $merchant_19_users,
            $merchant_20_users,
        ]);
    }
}
