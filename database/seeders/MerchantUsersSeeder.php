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
            [
                'merchant_id' => 1,
                'store_id' => 1,
                'user_id' => 1,
                'permission_applications' => true,
                'permission_deliveries' => true,
                'permission_manager' => true,
                'permission_upload_goods' => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'merchant_id' => 1,
                'store_id' => 2,
                'user_id' => 2,
                'permission_applications' => true,
                'permission_deliveries' => false,
                'permission_manager' => false,
                'permission_upload_goods' => false,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
        ];

        $merchant_2_users = [
            [
                'merchant_id' => 2,
                'store_id' => 3,
                'user_id' => 3,
                'permission_applications' => true,
                'permission_deliveries' => true,
                'permission_manager' => true,
                'permission_upload_goods' => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'merchant_id' => 2,
                'store_id' => 4,
                'user_id' => 4,
                'permission_applications' => true,
                'permission_deliveries' => false,
                'permission_manager' => false,
                'permission_upload_goods' => false,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
        ];

        $merchant_3_users = [
            [
                'merchant_id' => 3,
                'store_id' => 5,
                'user_id' => 5,
                'permission_applications' => true,
                'permission_deliveries' => true,
                'permission_manager' => true,
                'permission_upload_goods' => true,
            ],
        ];

        DB::table('azo_merchant_accesses')->insert($merchant_1_users);
        DB::table('azo_merchant_accesses')->insert($merchant_2_users);
        DB::table('azo_merchant_accesses')->insert($merchant_3_users);
    }
}
