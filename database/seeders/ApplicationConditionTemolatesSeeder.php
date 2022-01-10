<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ApplicationConditionTemolatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $condition_template_1 = [
            'duration' => 1,
            'commission' => 7,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $condition_template_2 = [
            'duration' => 3,
            'commission' => 15,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $condition_template_3 = [
            'duration' => 6,
            'commission' => 25,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $condition_template_4 = [
            'duration' => 9,
            'commission' => 32,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $condition_template_5 = [
            'duration' => 12,
            'commission' => 38,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $condition_template_6 = [
            'duration' => 15,
            'commission' => 50,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        \DB::table('application_condition_templates')->insert([
            $condition_template_1,
            $condition_template_2,
            $condition_template_3,
            $condition_template_4,
            $condition_template_5,
            $condition_template_6,
        ]);
    }
}
