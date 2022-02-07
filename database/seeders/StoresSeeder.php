<?php

namespace Database\Seeders;

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
        $store_1 = [
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
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $store_2 = [
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
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $store_3 = [
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
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $store_4 = [
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
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $store_5 = [
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
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $store_6 = [
            'name' => 'DEADSHOT - Chirchik',
            'merchant_id' => 5,
            'is_main' => 1,
            'address' => 'Chirchikskiy rayon ulitsa heytovskaya',
            'region' => 'tashkent_city',
            'lat' => '10026',
            'long' => '20026',
            'phone' => '998998999911',
            'responsible_person' => 'Dima',
            'responsible_person_phone' => '998902937777',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $store_7 = [
            'name' => 'Mega-shop - TTZ-1',
            'merchant_id' => 6,
            'is_main' => 0,
            'address' => 'Mirzo-Ulugbek rayon ulitsa ttz-soz',
            'region' => 'tashkent_city',
            'lat' => '10027',
            'long' => '20027',
            'phone' => '998998914576',
            'responsible_person' => 'Dima',
            'responsible_person_phone' => '998903334178',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $store_8 = [
            'name' => 'Rolton - Kibray',
            'merchant_id' => 7,
            'is_main' => 1,
            'address' => 'Kibray rayon ulitsa novosebirskaya',
            'region' => 'tashkent_city',
            'lat' => '10028',
            'long' => '20028',
            'phone' => '998935837844',
            'responsible_person' => 'Artyom',
            'responsible_person_phone' => '998911555363',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $store_9 = [
            'name' => 'Rolton - oqtepa',
            'merchant_id' => 7,
            'is_main' => 1,
            'address' => 'Grozniy rayon ulitsa dobriy',
            'region' => 'tashkent_city',
            'lat' => '10029',
            'long' => '20029',
            'phone' => '998944447888',
            'responsible_person' => 'Artyom',
            'responsible_person_phone' => '998985444114',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $store_10 = [
            'name' => 'Rolton - TTZ-2',
            'merchant_id' => 7,
            'is_main' => 1,
            'address' => 'Mirzo-ulugbek rayon azamat kuchasi',
            'region' => 'tashkent_city',
            'lat' => '10030',
            'long' => '20030',
            'phone' => '998944447712',
            'responsible_person' => 'Artyom',
            'responsible_person_phone' => '998985444554',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $store_11 = [
            'name' => 'Korzinka.uz - Chilonzor',
            'merchant_id' => 8,
            'is_main' => 0,
            'address' => 'Chilonzarskiy rayon ulitsa salom',
            'region' => 'tashkent_city',
            'lat' => '10031',
            'long' => '20031',
            'phone' => '998937899871',
            'responsible_person' => 'Artyom',
            'responsible_person_phone' => '998977894562',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $store_12 = [
            'name' => 'Korzinka.uz - TTZ-2',
            'merchant_id' => 8,
            'is_main' => 1,
            'address' => 'Mirzo-Ulugbek rayon Axror-kuchasi',
            'region' => 'tashkent_city',
            'lat' => '10032',
            'long' => '20032',
            'phone' => '998902288724',
            'responsible_person' => 'Artyom',
            'responsible_person_phone' => '998911466712',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $store_13 = [
            'name' => 'Korzinka.uz - Nukus',
            'merchant_id' => 8,
            'is_main' => 1,
            'address' => 'Uzbekistan rayon ulitsa tashkent',
            'region' => 'karakolpokstan',
            'lat' => '10033',
            'long' => '20033',
            'phone' => '998947145612',
            'responsible_person' => 'Artyom',
            'responsible_person_phone' => '998985444554',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $store_14 = [
            'name' => 'Korzinka.uz - Samarkand',
            'merchant_id' => 8,
            'is_main' => 0,
            'address' => 'Samarkand rayon ulitsa samarkandskaya',
            'region' => 'samarkand_city',
            'lat' => '10034',
            'long' => '20034',
            'phone' => '998947782458',
            'responsible_person' => 'Artyom',
            'responsible_person_phone' => '998901457896',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $store_15 = [
            'name' => 'Huawei Store - Buyuk Ipak yoli',
            'merchant_id' => 9,
            'is_main' => 1,
            'address' => 'Mirzo-Ulugbek rayon ulitsa buyuk ipak yoli',
            'region' => 'tashkent_city',
            'lat' => '10035',
            'long' => '20035',
            'phone' => '998966541237',
            'responsible_person' => 'Artyom',
            'responsible_person_phone' => '998968881212',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $store_16 = [
            'name' => 'Huawei Store - Kushbegi',
            'merchant_id' => 9,
            'is_main' => 0,
            'address' => 'Chilonzarskiy rayon ulitsa Kushbegi',
            'region' => 'tashkent_city',
            'lat' => '10036',
            'long' => '20036',
            'phone' => '998901236547',
            'responsible_person' => 'Artyom',
            'responsible_person_phone' => '998901114554',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $store_17 = [
            'name' => 'Huawei Store - Chirchik',
            'merchant_id' => 9,
            'is_main' => 0,
            'address' => 'Chirchikskiy rayon chirchik kuchasi',
            'region' => 'tashkent_city',
            'lat' => '10037',
            'long' => '20037',
            'phone' => '998944557878',
            'responsible_person' => 'Artyom',
            'responsible_person_phone' => '998968881200',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $store_18 = [
            'name' => 'Oppo - Malika',
            'merchant_id' => 10,
            'is_main' => 1,
            'address' => 'Malika rayon ulitsa tash',
            'region' => 'tashkent_city',
            'lat' => '10038',
            'long' => '20038',
            'phone' => '998945122222',
            'responsible_person' => 'Artyom',
            'responsible_person_phone' => '998944588998',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $store_19 = [
            'name' => 'Oppo - TTZ-4',
            'merchant_id' => 10,
            'is_main' => 1,
            'address' => 'Mirzo-Ulugbek rayon Azamat kuchasi',
            'region' => 'tashkent_city',
            'lat' => '10039',
            'long' => '20039',
            'phone' => '9989114557874',
            'responsible_person' => 'Artyom',
            'responsible_person_phone' => '998901477895',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $store_20 = [
            'name' => 'Oppo - Buyuk Ipak yoli',
            'merchant_id' => 10,
            'is_main' => 1,
            'address' => 'Mirzo-Ulugbek rayon ulitsa buyuk ipak yoli',
            'region' => 'tashkent_city',
            'lat' => '10040',
            'long' => '20040',
            'phone' => '998999894512',
            'responsible_person' => 'Artyom',
            'responsible_person_phone' => '998912525471',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        $store_21 = [
            'name' => 'GSHOP - Buyuk Ipak yoli',
            'merchant_id' => 4,
            'is_main' => 1,
            'address' => 'Mirzo-Ulugbek rayon ulitsa buyuk ipak yoli',
            'region' => 'tashkent_city',
            'lat' => '10041',
            'long' => '20041',
            'phone' => '99899984512',
            'responsible_person' => 'Artyom',
            'responsible_person_phone' => '998912524478',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ];

        DB::table('stores')->insert([
            $store_1,
            $store_2,
            $store_3,
            $store_4,
            $store_5,
            $store_6,
            $store_7,
            $store_8,
            $store_9,
            $store_10,
            $store_11,
            $store_12,
            $store_13,
            $store_14,
            $store_15,
            $store_16,
            $store_17,
            $store_18,
            $store_19,
            $store_20,
            $store_21,
        ]);
    }
}
