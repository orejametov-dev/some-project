<?php

namespace Database\Seeders;

use App\Modules\Merchants\Models\Merchant;
use Carbon\Carbon;
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
            'merchant_type' => Merchant::class,
            'tag_id' => 2,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $merchant_1_tag_3 = [
            'merchant_id' => 1,
            'merchant_type' => Merchant::class,
            'tag_id' => 3,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];


        $merchant_2_tag_4 = [
            'merchant_id' => 2,
            'merchant_type' => Merchant::class,
            'tag_id' => 4,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];


        $merchant_4_tag_1 = [
            'merchant_id' => 4,
            'merchant_type' => Merchant::class,
            'tag_id' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        DB::table('merchant_tag')->insert([
            $merchant_1_tag_2,
            $merchant_1_tag_3,
            $merchant_2_tag_4,
            $merchant_4_tag_1,
        ]);
    }
}
