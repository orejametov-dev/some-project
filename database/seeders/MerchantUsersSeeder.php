<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MerchantUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $merchant_1_users = [
            'merchant_id' => 1,
            'store_id' => 1,
            'user_id' => 1,
            'user_name' => 'Abror',
            'phone' => '+998906554411',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'company_user_id' => 1,
        ];
        $merchant_2_users = [
            'merchant_id' => 1,
            'store_id' => 2,
            'user_id' => 2,
            'user_name' => 'Axror',
            'phone' => '+998906554478',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'company_user_id' => 1,

        ];

        $merchant_3_users = [
            'merchant_id' => 2,
            'store_id' => 3,
            'user_id' => 3,
            'user_name' => 'Anton',
            'phone' => '+998999895512',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'company_user_id' => 2,
        ];
        $merchant_4_users = [
            'merchant_id' => 2,
            'store_id' => 4,
            'user_id' => 4,
            'user_name' => 'Dima',
            'phone' => '+998917884545',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'company_user_id' => 2,
        ];

        $merchant_5_users = [
            'merchant_id' => 3,
            'store_id' => 5,
            'user_id' => 5,
            'user_name' => 'Anton',
            'phone' => '+998914455445',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'company_user_id' => 3,
        ];

        $merchant_6_users = [
            'merchant_id' => 3,
            'store_id' => 5,
            'user_id' => 16,
            'user_name' => 'Shox',
            'phone' => '+998906554422',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'company_user_id' => 3,
        ];

        $merchant_7_users = [
            'merchant_id' => 5,
            'store_id' => 6,
            'user_id' => 6,
            'user_name' => 'Shox2',
            'phone' => '+998906553333',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'company_user_id' => 5,
        ];

        $merchant_8_users = [
            'merchant_id' => 5,
            'store_id' => 6,
            'user_id' => 7,
            'user_name' => 'Shox3',
            'phone' => '+8898656521',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'company_user_id' => 5,
        ];

        $merchant_9_users = [
            'merchant_id' => 6,
            'store_id' => 7,
            'user_id' => 8,
            'user_name' => 'Dmitriy',
            'phone' => '+8898656523',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'company_user_id' => 6,
        ];

        $merchant_10_users = [
            'merchant_id' => 6,
            'store_id' => 7,
            'user_id' => 9,
            'user_name' => 'Rodion',
            'phone' => '+8898902221447',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'company_user_id' => 6,
        ];

        $merchant_11_users = [
            'merchant_id' => 7,
            'store_id' => 8,
            'user_id' => 10,
            'user_name' => 'Anotoliy',
            'phone' => '+88986567781',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'company_user_id' => 7,
        ];

        $merchant_12_users = [
            'merchant_id' => 7,
            'store_id' => 9,
            'user_id' => 11,
            'user_name' => 'Anotoliy2',
            'phone' => '+8898656524',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'company_user_id' => 7,
        ];

        $merchant_13_users = [
            'merchant_id' => 8,
            'store_id' => 11,
            'user_id' => 13,
            'user_name' => 'Alesya',
            'phone' => '+8898656525',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'company_user_id' => 8,
        ];

        $merchant_14_users = [
            'merchant_id' => 8,
            'store_id' => 12,
            'user_id' => 14,
            'user_name' => 'Alesya2',
            'phone' => '+8898656526',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'company_user_id' => 8,
        ];

        $merchant_15_users = [
            'merchant_id' => 9,
            'store_id' => 15,
            'user_id' => 17,
            'user_name' => 'Konstantin',
            'phone' => '+8898656527',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'company_user_id' => 9,
        ];

        $merchant_16_users = [
            'merchant_id' => 9,
            'store_id' => 16,
            'user_id' => 18,
            'user_name' => 'Radj',
            'phone' => '+8898656528',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'company_user_id' => 9,
        ];

        $merchant_17_users = [
            'merchant_id' => 10,
            'store_id' => 18,
            'user_id' => 19,
            'user_name' => 'Kontanten',
            'phone' => '+8898656529',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'company_user_id' => 10,
        ];

        $merchant_18_users = [
            'merchant_id' => 10,
            'store_id' => 19,
            'user_id' => 20,
            'user_name' => 'Konstantin3',
            'phone' => '+8898656600',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'company_user_id' => 10,
        ];

        $merchant_19_users = [
            'merchant_id' => 4,
            'store_id' => 21,
            'user_id' => 21,
            'user_name' => 'Radj2',
            'phone' => '+889865661',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'company_user_id' => 4,
        ];

        $merchant_20_users = [
            'merchant_id' => 4,
            'store_id' => 21,
            'user_id' => 22,
            'user_name' => 'Gulchitay',
            'phone' => '+8898656666',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'company_user_id' => 4,
        ];

        DB::table('azo_merchant_accesses')->insert([
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
