<?php

namespace Database\Seeders;

use App\Modules\Merchants\Models\Condition;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApplicationConditionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $merchant_1_conditions = [
            [
                'merchant_id' => 1,
                'store_id' => 1,
                'duration' => 6,
                'commission' => 10,
                'active' => 1,
                'discount' => 25,
                'is_promotional' => 0,
                'special_offer' => 'no-name',
            ],
            [
                'merchant_id' => 1,
                'store_id' => 1,
                'duration' => 9,
                'commission' => 10,
                'active' => 1,
                'discount' => 32,
                'is_promotional' => 0,
                'special_offer' => 'no-name',
            ],
            [
                'merchant_id' => 1,
                'store_id' => 1,
                'duration' => 12,
                'commission' => 10,
                'active' => 1,
                'discount' => 38,
                'is_promotional' => 0,
                'special_offer' => 'no-name',
            ],
        ];

        $merchant_2_conditions = [
            [
                'merchant_id' => 2,
                'store_id' => 2,
                'duration' => 6,
                'commission' => 10,
                'active' => 1,
                'discount' => 25,
                'is_promotional' => 0,
                'special_offer' => 'no-name',
            ],
            [
                'merchant_id' => 2,
                'store_id' => 2,
                'duration' => 9,
                'commission' => 10,
                'active' => 1,
                'discount' => 32,
                'is_promotional' => 0,
                'special_offer' => 'no-name',
            ],
            [
                'merchant_id' => 2,
                'store_id' => 2,
                'duration' => 12,
                'commission' => 10,
                'active' => 1,
                'discount' => 38,
                'is_promotional' => 0,
                'special_offer' => 'no-name',
            ],
        ];

        $merchant_3_conditions = [
            [
                'merchant_id' => 3,
                'store_id' => 3,
                'duration' => 6,
                'commission' => 10,
                'active' => 1,
                'discount' => 25,
                'is_promotional' => 0,
                'special_offer' => 'no-name',
            ],
            [
                'merchant_id' => 3,
                'store_id' => 3,
                'duration' => 9,
                'commission' => 10,
                'active' => 1,
                'discount' => 32,
                'is_promotional' => 0,
                'special_offer' => 'no-name',
            ],
            [
                'merchant_id' => 3,
                'store_id' => 3,
                'duration' => 12,
                'commission' => 10,
                'active' => 1,
                'discount' => 38,
                'is_promotional' => 0,
                'special_offer' => 'no-name',
            ],
        ];

        DB::table('application_conditions')->insert($merchant_1_conditions);
        DB::table('application_conditions')->insert($merchant_2_conditions);
        DB::table('application_conditions')->insert($merchant_3_conditions);
    }
}
