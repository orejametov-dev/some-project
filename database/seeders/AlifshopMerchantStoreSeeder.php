<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlifshopMerchantStoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $alifshop_merchant_store_1 =  [
            'name' => 'Idea - Nurafshon',
            'alifshop_merchant_id' => 1,
            'is_main' => 1,
            'address' => 'ulitsa Pushkina, dom Kolotushkina',
            'region' => 'tashkent_city',
            'lat' => '10020',
            'long' => '20020',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $alifshop_merchant_store_2 =  [
            'name' => 'Idea - Oqtepa',
            'alifshop_merchant_id' => 1,
            'is_main' => 0,
            'address' => 'ulitsa Pushkina, dom Kolotushkina',
            'region' => 'tashkent_city',
            'lat' => '10021',
            'long' => '20021',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $alifshop_merchant_store_3 =  [
            'name' => 'Arena Markaz - Compas',
            'alifshop_merchant_id' => 2,
            'is_main' => 1,
            'address' => 'ulitsa Pushkina, dom Kolotushkina',
            'region' => 'tashkent_city',
            'lat' => '10022',
            'long' => '20022',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $alifshop_merchant_store_4 =  [
            'name' => 'Arena Markaz - Malika',
            'alifshop_merchant_id' => 2,
            'is_main' => 0,
            'address' => 'ulitsa Pushkina, dom Kolotushkina',
            'region' => 'tashkent_city',
            'lat' => '10023',
            'long' => '20023',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $alifshop_merchant_store_5 =  [
            'name' => 'Mobilezone - Oybek',
            'alifshop_merchant_id' => 3,
            'is_main' => 1,
            'address' => 'ulitsa Pushkina, dom Kolotushkina',
            'region' => 'tashkent_city',
            'lat' => '10024',
            'long' => '20024',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $alifshop_merchant_store_6 =  [
            'name' => 'DEADSHOT - Chirchik',
            'alifshop_merchant_id' => 5,
            'is_main' => 1,
            'address' => 'Chirchikskiy rayon ulitsa heytovskaya',
            'region' => 'tashkent_city',
            'lat' => '10026',
            'long' => '20026',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $alifshop_merchant_store_7 =  [
            'name' => 'Mega-shop - TTZ-1',
            'alifshop_merchant_id' => 6,
            'is_main' => 0,
            'address' => 'Mirzo-Ulugbek rayon ulitsa ttz-soz',
            'region' => 'tashkent_city',
            'lat' => '10027',
            'long' => '20027',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $alifshop_merchant_store_8 =  [
            'name' => 'Rolton - Kibray',
            'alifshop_merchant_id' => 7,
            'is_main' => 1,
            'address' => 'Kibray rayon ulitsa novosebirskaya',
            'region' => 'tashkent_city',
            'lat' => '10028',
            'long' => '20028',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $alifshop_merchant_store_9 =  [
            'name' => 'Rolton - oqtepa',
            'alifshop_merchant_id' => 7,
            'is_main' => 1,
            'address' => 'Grozniy rayon ulitsa dobriy',
            'region' => 'tashkent_city',
            'lat' => '10029',
            'long' => '20029',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $alifshop_merchant_store_10 =  [
            'name' => 'Rolton - TTZ-2',
            'alifshop_merchant_id' => 7,
            'is_main' => 1,
            'address' => 'Mirzo-ulugbek rayon azamat kuchasi',
            'region' => 'tashkent_city',
            'lat' => '10030',
            'long' => '20030',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $alifshop_merchant_store_11 =  [
            'name' => 'Korzinka.uz - Chilonzor',
            'alifshop_merchant_id' => 8,
            'is_main' => 0,
            'address' => 'Chilonzarskiy rayon ulitsa salom',
            'region' => 'tashkent_city',
            'lat' => '10031',
            'long' => '20031',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $alifshop_merchant_store_12 =  [
            'name' => 'Korzinka.uz - TTZ-2',
            'alifshop_merchant_id' => 8,
            'is_main' => 1,
            'address' => 'Mirzo-Ulugbek rayon Axror-kuchasi',
            'region' => 'tashkent_city',
            'lat' => '10032',
            'long' => '20032',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $alifshop_merchant_store_13 =  [
            'name' => 'Korzinka.uz - Nukus',
            'alifshop_merchant_id' => 8,
            'is_main' => 1,
            'address' => 'Uzbekistan rayon ulitsa tashkent',
            'region' => 'karakolpokstan',
            'lat' => '10033',
            'long' => '20033',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $alifshop_merchant_store_14  =  [
            'name' => 'Korzinka.uz - Samarkand',
            'alifshop_merchant_id' => 8,
            'is_main' => 0,
            'address' => 'Samarkand rayon ulitsa samarkandskaya',
            'region' => 'samarkand_city',
            'lat' => '10034',
            'long' => '20034',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $alifshop_merchant_store_15  =  [
            'name' => 'Huawei Store - Buyuk Ipak yoli',
            'alifshop_merchant_id' => 9,
            'is_main' => 1,
            'address' => 'Mirzo-Ulugbek rayon ulitsa buyuk ipak yoli',
            'region' => 'tashkent_city',
            'lat' => '10035',
            'long' => '20035',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $alifshop_merchant_store_16  =  [
            'name' => 'Huawei Store - Kushbegi',
            'alifshop_merchant_id' => 9,
            'is_main' => 0,
            'address' => 'Chilonzarskiy rayon ulitsa Kushbegi',
            'region' => 'tashkent_city',
            'lat' => '10036',
            'long' => '20036',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $alifshop_merchant_store_17  =  [
            'name' => 'Huawei Store - Chirchik',
            'alifshop_merchant_id' => 9,
            'is_main' => 0,
            'address' => 'Chirchikskiy rayon chirchik kuchasi',
            'region' => 'tashkent_city',
            'lat' => '10037',
            'long' => '20037',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $alifshop_merchant_store_18  =  [
            'name' => 'Oppo - Malika',
            'alifshop_merchant_id' => 10,
            'is_main' => 1,
            'address' => 'Malika rayon ulitsa tash',
            'region' => 'tashkent_city',
            'lat' => '10038',
            'long' => '20038',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $alifshop_merchant_store_19  =  [
            'name' => 'Oppo - TTZ-4',
            'alifshop_merchant_id' => 10,
            'is_main' => 1,
            'address' => 'Mirzo-Ulugbek rayon Azamat kuchasi',
            'region' => 'tashkent_city',
            'lat' => '10039',
            'long' => '20039',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $alifshop_merchant_store_20  =  [
            'name' => 'Oppo - Buyuk Ipak yoli',
            'alifshop_merchant_id' => 10,
            'is_main' => 1,
            'address' => 'Mirzo-Ulugbek rayon ulitsa buyuk ipak yoli',
            'region' => 'tashkent_city',
            'lat' => '10040',
            'long' => '20040',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $alifshop_merchant_store_21  =  [
            'name' => 'GSHOP - Buyuk Ipak yoli',
            'alifshop_merchant_id' => 4,
            'is_main' => 1,
            'address' => 'Mirzo-Ulugbek rayon ulitsa buyuk ipak yoli',
            'region' => 'tashkent_city',
            'lat' => '10041',
            'long' => '20041',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        DB::table('alifshop_merchant_stores')->insert([
            $alifshop_merchant_store_1,
            $alifshop_merchant_store_2,
            $alifshop_merchant_store_3,
            $alifshop_merchant_store_4,
            $alifshop_merchant_store_5,
            $alifshop_merchant_store_6,
            $alifshop_merchant_store_7,
            $alifshop_merchant_store_8,
            $alifshop_merchant_store_9,
            $alifshop_merchant_store_10,
            $alifshop_merchant_store_11,
            $alifshop_merchant_store_12,
            $alifshop_merchant_store_13,
            $alifshop_merchant_store_14,
            $alifshop_merchant_store_15,
            $alifshop_merchant_store_16,
            $alifshop_merchant_store_17,
            $alifshop_merchant_store_18,
            $alifshop_merchant_store_19,
            $alifshop_merchant_store_20,
            $alifshop_merchant_store_21
        ]);
    }
}
