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
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        DB::table('merchant_additional_agreements')->insert([
            $additional_agreement_1
        ]);
    }
}
