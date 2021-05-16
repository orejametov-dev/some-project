<?php

namespace Database\Seeders;

use App\Modules\Merchants\Models\MerchantUser;
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
            ],
            [
                'merchant_id' => 1,
                'store_id' => 1,
                'user_id' => 2,
                'permission_applications' => true,
                'permission_deliveries' => false,
                'permission_manager' => false,
                'permission_upload_goods' => false,
            ],
            [
                'merchant_id' => 1,
                'store_id' => 2,
                'user_id' => 1,
                'permission_applications' => true,
                'permission_deliveries' => true,
                'permission_manager' => true,
                'permission_upload_goods' => true,
            ],
            [
                'merchant_id' => 1,
                'store_id' => 2,
                'user_id' => 2,
                'permission_applications' => true,
                'permission_deliveries' => false,
                'permission_manager' => false,
                'permission_upload_goods' => false,
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
            ],
            [
                'merchant_id' => 2,
                'store_id' => 3,
                'user_id' => 4,
                'permission_applications' => true,
                'permission_deliveries' => false,
                'permission_manager' => false,
                'permission_upload_goods' => false,
            ],
            [
                'merchant_id' => 2,
                'store_id' => 4,
                'user_id' => 3,
                'permission_applications' => true,
                'permission_deliveries' => true,
                'permission_manager' => true,
                'permission_upload_goods' => true,
            ],
            [
                'merchant_id' => 2,
                'store_id' => 4,
                'user_id' => 4,
                'permission_applications' => true,
                'permission_deliveries' => false,
                'permission_manager' => false,
                'permission_upload_goods' => false,
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

        DB::table('merchant_users')->insert($merchant_1_users);
        DB::table('merchant_users')->insert($merchant_2_users);
        DB::table('merchant_users')->insert($merchant_3_users);
    }
}
