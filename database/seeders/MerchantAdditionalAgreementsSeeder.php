<?php

namespace Database\Seeders;

use App\Modules\Merchants\Models\AdditionalAgreement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MerchantAdditionalAgreementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $additional_agreements= [
            [
                'merchant_id' => 1,
                'number' => '111111',
                'registration_date' => '2021-02-13 00:00:00',
                'limit' => 321400,
                'rest_limit' => 99980621400,
            ],
            [
                'merchant_id' => 2,
                'number' => '222222',
                'registration_date' => '2021-02-13 00:00:00',
                'limit' => 321400,
                'rest_limit' => 99980621400,
            ],
            [
                'merchant_id' => 3,
                'number' => '333333',
                'registration_date' => '2021-02-13 00:00:00',
                'limit' => 321400,
                'rest_limit' => 99980621400,
            ],
        ];

        DB::table('additional_agreements')->insert($additional_agreements);
    }
}
