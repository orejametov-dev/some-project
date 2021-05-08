<?php

namespace Database\Seeders;

use App\Modules\Merchants\Models\MerchantInfo;
use Illuminate\Database\Seeder;

class MerchantInfosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $merchant_infos = [
            [
                'legal_name' => 'lorem ipsum',
                'director_name' => 'lorem ipsum',
                'phone' => '998988784388',
                'vat_number' => '312222222222',
                'mfo' => '12333',
                'tin' => '321111111',
                'oked' => '43222',
                'address' => 'Toshkent shahar',
                'bank_account' => '21312321321321213212',
                'bank_name' => 'Asaka',
                'contract_number' => 111,
                'merchant_id' => 1,
                'limit' => 100000000000,
                'contract_date' => '2021-03-29 00:00:00',
                'rest_limit' => 81634895479,
            ],
            [
                'legal_name' => 'lorem ipsum2',
                'director_name' => 'lorem ipsum2',
                'phone' => '998988784388',
                'vat_number' => '312222222222',
                'mfo' => '12333',
                'tin' => '321111111',
                'oked' => '43222',
                'address' => 'Toshkent shahar2',
                'bank_account' => '21312321321321213212',
                'bank_name' => 'Asaka2',
                'contract_number' => 222,
                'merchant_id' => 2,
                'limit' => 100000000000,
                'contract_date' => '2021-03-29 00:00:00',
                'rest_limit' => 81634895479,
            ],
            [
                'legal_name' => 'lorem ipsum3',
                'director_name' => 'lorem ipsum3',
                'phone' => '998988784388',
                'vat_number' => '312222222222',
                'mfo' => '12333',
                'tin' => '321111111',
                'oked' => '43222',
                'address' => 'Toshkent shahar3',
                'bank_account' => '21312321321321213212',
                'bank_name' => 'Asaka3',
                'contract_number' => 333,
                'merchant_id' => 3,
                'limit' => 100000000000,
                'contract_date' => '2021-03-29 00:00:00',
                'rest_limit' => 81634895479,
            ],
        ];

        MerchantInfo::query()->insert($merchant_infos);
    }
}
