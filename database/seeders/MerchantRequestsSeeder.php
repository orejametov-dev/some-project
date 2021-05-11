<?php

namespace Database\Seeders;

use App\Modules\Merchants\Models\Request;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MerchantRequestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $merchant_requests = [
            [
                'name' => 'idea-last',
                'information' => 'some info',
                'legal_name' => 'OOO New Idea',
                'user_name' => 'Palonchi Palonchiev',
                'user_phone' => '998995444111',
                'status_id' => 1,
                'region' => 'Navoiy viloyati',
                'engaged_by_id' => 1,
                'engaged_at' => '2021-05-05 16:38:56',
                'status_updated_at' => '2021-05-06 16:38:56',
            ],
            [
                'name' => 'idea-last2',
                'information' => 'some info',
                'legal_name' => 'OOO New Idea',
                'user_name' => 'Palonchi Palonchiev',
                'user_phone' => '998995444111',
                'status_id' => 2,
                'region' => 'Navoiy viloyati',
                'engaged_by_id' => 2,
                'engaged_at' => '2021-05-05 16:38:56',
                'status_updated_at' => '2021-05-06 16:38:56',
            ],
            [
                'name' => 'idea-3',
                'information' => 'some info',
                'legal_name' => 'OOO New Idea3',
                'user_name' => 'Palonchi Palonchiev',
                'user_phone' => '998995444111',
                'status_id' => 3,
                'region' => 'Navoiy viloyati',
                'engaged_by_id' => 3,
                'engaged_at' => '2021-05-05 16:38:56',
                'status_updated_at' => '2021-05-06 16:38:56',
            ],
        ];

        DB::table('merchant_requests')->insert($merchant_requests);
    }
}
