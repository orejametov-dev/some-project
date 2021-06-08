<?php

namespace Database\Seeders;

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
        ];

        $tag_2 = [
            'id' => 2,
            'title' => 'Бытовая техника',
        ];

        $tag_3 = [
            'id' => 3,
            'title' => 'Смартфоны',
        ];

        $tag_4 = [
            'id' => 4,
            'title' => 'Компьютеры',
        ];

        DB::table('merchant_tags')->insert([
            $tag_1,
            $tag_2,
            $tag_3,
            $tag_4,
        ]);
    }
}
