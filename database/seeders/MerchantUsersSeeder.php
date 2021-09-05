<?php

namespace Database\Seeders;

use App\Modules\Merchants\Models\MerchantUser;
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
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'merchant_id' => 1,
                'store_id' => 2,
                'user_id' => 2,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
        ];

        $merchant_2_users = [
            [
                'merchant_id' => 2,
                'store_id' => 3,
                'user_id' => 3,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'merchant_id' => 2,
                'store_id' => 4,
                'user_id' => 4,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
        ];

        $merchant_3_users = [
            [
                'merchant_id' => 3,
                'store_id' => 5,
                'user_id' => 5,
            ],
        ];

        DB::table('merchant_users')->insert($merchant_1_users);
        DB::table('merchant_users')->insert($merchant_2_users);
        DB::table('merchant_users')->insert($merchant_3_users);
    }
}
