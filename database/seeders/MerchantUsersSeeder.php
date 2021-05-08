<?php

namespace Database\Seeders;

use App\Modules\Merchants\Models\MerchantUser;
use Illuminate\Database\Seeder;

class MerchantUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $merchant_users = [
            [
                'merchant_id' => 1,
                'store_id' => 1,
                'user_id' => 1,
                'permission_applications' => true,
                'permission_deliveries' => true,
                'permission_orders' => true,
                'permission_manager' => true,
                'permission_upload_goods' => true,
                'permission_oso' => true,
            ],
            [
                'merchant_id' => 2,
                'store_id' => 2,
                'user_id' => 2,
                'permission_applications' => true,
                'permission_deliveries' => false,
                'permission_orders' => false,
                'permission_manager' => false,
                'permission_upload_goods' => false,
                'permission_oso' => false,
            ],
            [
                'merchant_id' => 3,
                'store_id' => 3,
                'user_id' => 3,
                'permission_applications' => false,
                'permission_deliveries' => false,
                'permission_orders' => false,
                'permission_manager' => false,
                'permission_upload_goods' => false,
                'permission_oso' => false,
            ],
        ];

        MerchantUser::query()->insert($merchant_users);
    }
}
