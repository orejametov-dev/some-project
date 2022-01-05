<?php

namespace Database\Seeders;

use App\Modules\Merchants\Models\AdditionalAgreement;
use Carbon\Carbon;
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
        $additional_agreement_1 = [
            'merchant_id' => 1,
            'number' => '100010',
            'registration_date' => '2021-05-14 00:00:00',
            'limit' => 1000000000,
            'document_type' => 'old',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $additional_agreement_2 = [
            'merchant_id' => 2,
            'number' => '1111111',
            'registration_date' => '2021-05-14 00:00:00',
            'limit' => 500000000,
            'document_type' => 'old',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $additional_agreement_3 = [
            'merchant_id' => 1,
            'number' => '100013',
            'registration_date' => '2021-05-14 00:00:00',
            'limit' => 6000000000,
            'document_type' => 'new',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        DB::table('merchant_additional_agreements')->insert([
            $additional_agreement_1,
            $additional_agreement_2,
            $additional_agreement_3
        ]);
    }
}
