<?php

namespace Database\Seeders;

use App\Modules\Merchants\Models\Store;
use Carbon\Carbon;
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

        $store_1 =  [
            'name' => 'Idea - Nurafshon',
            'merchant_id' => 1,
            'is_main' => 1,
            'address' => 'ulitsa Pushkina, dom Kolotushkina',
            'region' => 'tashkent_city',
            'lat' => '10020',
            'long' => '20020',
            'phone' => '998901112233',
            'responsible_person' => 'Shokhrukh',
            'responsible_person_phone' => '998901112233',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $store_2 =  [
            'name' => 'Idea - Oqtepa',
            'merchant_id' => 1,
            'is_main' => 0,
            'address' => 'ulitsa Pushkina, dom Kolotushkina',
            'region' => 'tashkent_city',
            'lat' => '10021',
            'long' => '20021',
            'phone' => '998901112233',
            'responsible_person' => 'Iskandar',
            'responsible_person_phone' => '998901112233',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $store_3 =  [
            'name' => 'Arena Markaz - Compas',
            'merchant_id' => 2,
            'is_main' => 1,
            'address' => 'ulitsa Pushkina, dom Kolotushkina',
            'region' => 'tashkent_city',
            'lat' => '10022',
            'long' => '20022',
            'phone' => '998912223344',
            'responsible_person' => 'Bekhzod',
            'responsible_person_phone' => '998912223344',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $store_4 =  [
            'name' => 'Arena Markaz - Malika',
            'merchant_id' => 2,
            'is_main' => 0,
            'address' => 'ulitsa Pushkina, dom Kolotushkina',
            'region' => 'tashkent_city',
            'lat' => '10023',
            'long' => '20023',
            'phone' => '998912223344',
            'responsible_person' => 'Vera',
            'responsible_person_phone' => '998912223344',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $store_5 =  [
            'name' => 'Mobilezone - Oybek',
            'merchant_id' => 3,
            'is_main' => 1,
            'address' => 'ulitsa Pushkina, dom Kolotushkina',
            'region' => 'tashkent_city',
            'lat' => '10024',
            'long' => '20024',
            'phone' => '998923334455',
            'responsible_person' => 'Sabokhat',
            'responsible_person_phone' => '998923334455',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        DB::table('stores')->insert([
            $store_1,
            $store_2,
            $store_3,
            $store_4,
            $store_5,
        ]);
    }
}
