<?php

namespace Database\Seeders;

use App\Modules\Merchants\Models\Store;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stores = [
            [
                'name' => 'Crediton - Online',
                'merchant_id' => 1,
                'is_main' => 1,
                'address' => 'ulitsa Pushkina, dom Kolotushkina',
                'region' => 'tashkent_city',
                'lat' => '1000',
                'long' => '2000',
                'phone' => '998781130408',
                'responsible_person' => 'Palonchi Palonchiev',
                'responsible_person_phone' => '998901234567',
            ],
            [
                'name' => 'Arena Markaz - Compas',
                'merchant_id' => 2,
                'is_main' => 1,
                'address' => 'ulitsa Pushkina, dom Kolotushkina',
                'region' => 'tashkent_city',
                'lat' => '2000',
                'long' => '3000',
                'phone' => '998712002020',
                'responsible_person' => 'Palonchi Palonchiev2',
                'responsible_person_phone' => '998911234567',
            ],
            [
                'name' => 'Idea - Nurafshon',
                'merchant_id' => 3,
                'is_main' => 1,
                'address' => 'ulitsa Pushkina, dom Kolotushkina',
                'region' => 'tashkent_city',
                'lat' => '4000',
                'long' => '5000',
                'phone' => '998712240000',
                'responsible_person' => 'Palonchi Palonchiev3',
                'responsible_person_phone' => '998921234567',
            ]
        ];

        DB::table('stores')->insert($stores);
    }
}
