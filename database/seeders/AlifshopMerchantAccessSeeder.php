<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlifshopMerchantAccessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $alifshop_merchant_1_users = [
            'alifshop_merchant_id' => 1,
            'alifshop_merchant_store_id' => 1,
            'company_user_id' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $alifshop_merchant_2_users = [
            'alifshop_merchant_id' => 1,
            'alifshop_merchant_store_id' => 2,
            'company_user_id' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $alifshop_merchant_3_users = [
            'alifshop_merchant_id' => 2,
            'aifshop_merchant_store_id' => 3,
            'company_user_id' => 2,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];
        $alifshop_merchant_4_users = [
            'alifshop_merchant_id' => 2,
            'alifshop_merchant_store_id' => 4,
            'company_user_id' => 2,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $alifshop_merchant_5_users = [
            'alifshop_merchant_id' => 3,
            'alifshop_merchant_store_id' => 5,
            'company_user_id' => 3,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),];

        $alifshop_merchant_6_users = [
            'alifshop_merchant_id' => 3,
            'alifshop_merchant_store_id' => 5,
            'company_user_id' => 3,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $alifshop_merchant_7_users = [
            'alifshop_merchant_id' => 5,
            'alifshop_merchant_store_id' => 6,
            'company_user_id' => 5,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),];

        $alifshop_merchant_8_users = [
            'alifshop_merchant_id' => 5,
            'alifshop_merchant_store_id' => 6,
            'company_user_id' => 5,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $alifshop_merchant_9_users = [
            'alifshop_merchant_id' => 6,
            'alifshop_merchant_store_id' => 7,
            'company_user_id' => 6,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $alifshop_merchant_10_users = [
            'alifshop_merchant_id' => 6,
            'alifshop_merchant_store_id' => 7,
            'company_user_id' => 6,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $alifshop_merchant_11_users = [
            'alifshop_merchant_id' => 7,
            'alifshop_merchant_store_id' => 8,
            'company_user_id' => 7,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $alifshop_merchant_12_users = [
            'alifshop_merchant_id' => 7,
            'alifshop_merchant_store_id' => 9,
            'company_user_id' => 7,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $alifshop_merchant_13_users = [
            'alifshop_merchant_id' => 8,
            'alifshop_merchant_store_id' => 11,
            'company_user_id' => 8,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $alifshop_merchant_14_users = [
            'alifshop_merchant_id' => 8,
            'alifshop_merchant_store_id' => 12,
            'company_user_id' => 8,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $alifshop_merchant_15_users = [
            'alifshop_merchant_id' => 9,
            'alifshop_merchant_store_id' => 15,
            'company_user_id' => 9,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $alifshop_merchant_16_users = [
            'alifshop_merchant_id' => 9,
            'alifshop_merchant_store_id' => 16,
            'company_user_id' => 9,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $alifshop_merchant_17_users = [
            'alifshop_merchant_id' => 10,
            'alifshop_merchant_store_id' => 18,
            'company_user_id' => 10,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $alifshop_merchant_18_users = [
            'alifshop_merchant_id' => 10,
            'alifshop_merchant_store_id' => 19,
            'company_user_id' => 10,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $alifshop_merchant_19_users = [
            'alifshop_merchant_id' => 4,
            'alifshop_merchant_store_id' => 21,
            'company_user_id' => 4,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $alifshop_merchant_20_users = [
            'alifshop_merchant_id' => 4,
            'alifshop_merchant_store_id' => 21,
            'company_user_id' => 4,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        DB::table('alifshop_merchant_accesses')->insert([
            $alifshop_merchant_1_users,
            $alifshop_merchant_2_users,
            $alifshop_merchant_3_users,
            $alifshop_merchant_4_users,
            $alifshop_merchant_5_users,
            $alifshop_merchant_6_users,
            $alifshop_merchant_7_users,
            $alifshop_merchant_8_users,
            $alifshop_merchant_9_users,
            $alifshop_merchant_10_users,
            $alifshop_merchant_11_users,
            $alifshop_merchant_12_users,
            $alifshop_merchant_13_users,
            $alifshop_merchant_14_users,
            $alifshop_merchant_15_users,
            $alifshop_merchant_16_users,
            $alifshop_merchant_17_users,
            $alifshop_merchant_18_users,
            $alifshop_merchant_19_users,
            $alifshop_merchant_20_users

        ]);
    }
}
