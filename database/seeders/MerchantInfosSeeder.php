<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MerchantInfosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $merchant_info_1 = [
            'director_name' => 'Ismoil Nosirov',
            'phone' => '998901112233',
            'vat_number' => '312738127000',
            'mfo' => '31212',
            'tin' => '381287654',
            'oked' => '89389',
            'address' => 'ulitsa Pushkina, dom Kolotushkina',
            'bank_account' => '89328948312909090111',
            'bank_name' => 'Asia Alliance Bank',
            'contract_number' => 100001,
            'merchant_id' => 1,
            'limit' => 1000000000,
            'contract_date' => '2021-05-14 00:00:00',
            'rest_limit' => null,
        ];

        $merchant_info_2 = [
            'director_name' => 'Ismoil Nosr',
            'phone' => '998912223344',
            'vat_number' => '312738127111',
            'mfo' => '31213',
            'tin' => '381287655',
            'oked' => '89390',
            'address' => 'ulitsa Pushkina, dom Kolotushkina',
            'bank_account' => '89328948312909090222',
            'bank_name' => 'Orient Finance Bank',
            'contract_number' => 100002,
            'merchant_id' => 2,
            'limit' => 1000000000,
            'contract_date' => '2021-05-14 00:00:01',
            'rest_limit' => null,
        ];

        $merchant_info_3 = [
            'director_name' => 'Nosr Ismoil',
            'phone' => '9989133323344',
            'vat_number' => '312738127222',
            'mfo' => '31214',
            'tin' => '381287656',
            'oked' => '89391',
            'address' => 'ulitsa Pushkina, dom Kolotushkina',
            'bank_account' => '89328948312909090333',
            'bank_name' => 'Anor bank',
            'contract_number' => 100003,
            'merchant_id' => 3,
            'limit' => 1000000000,
            'contract_date' => '2021-05-14 00:00:02',
            'rest_limit' => null,
        ];

        DB::table('merchant_infos')->insert([
            $merchant_info_1,
            $merchant_info_2,
            $merchant_info_3,
        ]);
    }
}
