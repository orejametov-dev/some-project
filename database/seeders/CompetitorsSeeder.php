<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompetitorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $competitor_1 =  [
            'name' => 'Intend',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $competitor_2 =  [
            'name' => 'Paymart',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $competitor_3 =  [
            'name' => 'Unired',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $competitor_4 =  [
            'name' => 'Vendoo',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $competitor_5 =  [
            'name' => 'Z market',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $competitor_6 =  [
            'name' => 'Anorbank',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $competitor_7 =  [
            'name' => 'Xalq bank',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        DB::table('competitors')->insert([
            $competitor_1,
            $competitor_2,
            $competitor_3,
            $competitor_4,
            $competitor_5,
            $competitor_6,
            $competitor_7
        ]);
    }
}
