<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MerchantTag extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $merchant_1_tag_2 = [
            'merchant_id' => 1,
            'tag_id' => 2
        ];

        $merchant_1_tag_3 = [
            'merchant_id' => 1,
            'tag_id' => 3
        ];

        $merchant_2_tag_3 = [
            'merchant_id' => 1,
            'tag_id' => 3
        ];

        $merchant_2_tag_4 = [
            'merchant_id' => 1,
            'tag_id' => 4
        ];

        $merchant_3_tag_4 = [
            'merchant_id' => 1,
            'tag_id' => 4
        ];

        $merchant_4_tag_1 = [
            'merchant_id' => 1,
            'tag_id' => 1
        ];

        DB::table('merchant_tag')->insert([
            $merchant_1_tag_2,
            $merchant_1_tag_3,
            $merchant_2_tag_3,
            $merchant_2_tag_4,
            $merchant_3_tag_4,
            $merchant_4_tag_1,
        ]);
    }
}
