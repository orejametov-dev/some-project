<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MerchantTags extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tag_1 = [
            'id' => 1,
            'title' => 'Не обработано',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $tag_2 = [
            'id' => 2,
            'title' => 'Бытовая техника',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $tag_3 = [
            'id' => 3,
            'title' => 'Смартфоны',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $tag_4 = [
            'id' => 4,
            'title' => 'Компьютеры',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        DB::table('merchant_tags')->insert([
            $tag_1,
            $tag_2,
            $tag_3,
            $tag_4,
        ]);
    }
}
