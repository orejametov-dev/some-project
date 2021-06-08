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

        $condition_1_month = [
            'duration' => 1,
            'commission' => 7,
            'discount' => 0,
            'active' => 1
        ];

        $condition_3_month = [
            'duration' => 3,
            'commission' => 15,
            'discount' => 0,
            'active' => 1
        ];

        $condition_6_month = [
            'duration' => 6,
            'commission' => 25,
            'discount' => 0,
            'active' => 1
        ];

        $condition_9_month = [
            'duration' => 9,
            'commission' => 32,
            'discount' => 0,
            'active' => 1
        ];

        $condition_12_month = [
            'duration' => 12,
            'commission' => 38,
            'discount' => 0,
            'active' => 1
        ];

        $condition_15_month = [
            'duration' => 15,
            'commission' => 47,
            'discount' => 0,
            'active' => 1
        ];

        $merchant_1_conditions = [
            array_merge($condition_1_month, ['merchant_id' => 1, 'store_id' => 1]),
            array_merge($condition_3_month, ['merchant_id' => 1, 'store_id' => 1]),
            array_merge($condition_6_month, ['merchant_id' => 1, 'store_id' => 1]),
            array_merge($condition_9_month, ['merchant_id' => 1, 'store_id' => 1]),
            array_merge($condition_12_month, ['merchant_id' => 1, 'store_id' => 1]),
            array_merge($condition_15_month, ['merchant_id' => 1, 'store_id' => 1]),
        ];

        $merchant_2_conditions = [
            ['merchant_id' => 2, 'store_id' => 3, 'duration' => 6, 'discount' => 10, 'commission' => 0, 'active' => 1],
            ['merchant_id' => 2, 'store_id' => 4, 'duration' => 12, 'discount' => 0, 'commission' => 35, 'active' => 1],
        ];

        $merchant_3_conditions = [
            array_merge($condition_1_month, ['merchant_id' => 3, 'store_id' => 5]),
            array_merge($condition_3_month, ['merchant_id' => 3, 'store_id' => 5]),
            array_merge($condition_6_month, ['merchant_id' => 3, 'store_id' => 5]),
            ['merchant_id' => 3, 'store_id' => 5, 'duration' => 9, 'discount' => 0, 'commission' => 34, 'active' => 1],
            ['merchant_id' => 3, 'store_id' => 5, 'duration' => 12, 'discount' => 0, 'commission' => 39, 'active' => 1],
            ['merchant_id' => 3, 'store_id' => 5, 'duration' => 15, 'discount' => 10, 'commission' => 0, 'active' => 1],
        ];

        DB::table('application_conditions')->insert($merchant_1_conditions);
        DB::table('application_conditions')->insert($merchant_2_conditions);
        DB::table('application_conditions')->insert($merchant_3_conditions);
    }
}
