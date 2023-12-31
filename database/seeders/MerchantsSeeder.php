<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MerchantsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $merchant_1 = [
            'id' => 1,
            'name' => 'Idea Store',
            'legal_name' => 'OOO "Idea"',
            'legal_name_prefix' => 'LLC',
            'token' => 'token-idea',
            'logo_url' => false, // new options
            'maintainer_id' => 1,
            'current_sales' => 1000000,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'company_id' => 1,
        ];

        $merchant_2 = [
            'id' => 2,
            'name' => 'Arena Markaz',
            'legal_name' => 'ООО «RETAIL OPERATION GROUP»',
            'legal_name_prefix' => 'LLC',
            'token' => 'token-arenamarkaz',
            'logo_url' => false,
            'maintainer_id' => 2,
            'current_sales' => 2000000,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'company_id' => 2,
        ];

        $merchant_3 = [
            'id' => 3,
            'name' => 'Mobilezone',
            'legal_name' => 'OOO "Mobilezone Store"',
            'legal_name_prefix' => 'LLC',
            'token' => 'token-mobilezone',
            'logo_url' => false,
            'maintainer_id' => 3,
            'current_sales' => 3000000,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'company_id' => 3,
        ];

        $merchant_4 = [
            'id' => 4,
            'name' => 'GSHOP',
            'legal_name' => 'OOO "GSHOP"',
            'legal_name_prefix' => 'LLC',
            'token' => 'token-gshop',
            'logo_url' => false,
            'maintainer_id' => 4,
            'current_sales' => 3000000,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'company_id' => 4,
        ];

        $merchant_5 = [
            'id' => 5,
            'name' => 'DEADSHOT',
            'legal_name' => 'OOO "DEADSHOT"',
            'legal_name_prefix' => 'LLC',
            'token' => 'token-deadshot',
            'logo_url' => false,
            'maintainer_id' => 5,
            'current_sales' => 50000000,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'company_id' => 5,
        ];

        $merchant_6 = [
            'id' => 6,
            'name' => 'Mega-shop',
            'legal_name' => 'OOO "League group"',
            'legal_name_prefix' => 'LLC',
            'token' => 'token-mega-shop',
            'logo_url' => false,
            'maintainer_id' => 6,
            'current_sales' => 20000000,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'company_id' => 6,
        ];

        $merchant_7 = [
            'id' => 7,
            'name' => 'Rolton',
            'legal_name' => 'OOO "ROLTON"',
            'legal_name_prefix' => 'LLC',
            'token' => 'token-rolton',
            'logo_url' => false,
            'maintainer_id' => 7,
            'current_sales' => 30000000,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'company_id' => 7,
        ];

        $merchant_8 = [
            'id' => 8,
            'name' => 'Korzinka.uz',
            'legal_name' => 'OOO "REDTAG GROUP"',
            'legal_name_prefix' => 'LLC',
            'token' => 'token-korzinkauz',
            'logo_url' => false,
            'maintainer_id' => 8,
            'current_sales' => 100000000,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'company_id' => 8,
        ];

        $merchant_9 = [
            'id' => 9,
            'name' => 'Huawei Store',
            'legal_name' => 'OOO "HUAWEI"',
            'legal_name_prefix' => 'LLC',
            'token' => 'token-huaweistore',
            'logo_url' => false,
            'maintainer_id' => 9,
            'current_sales' => 500000000,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'company_id' => 9,
        ];

        $merchant_10 = [
            'id' => 10,
            'name' => 'Oppo',
            'legal_name' => 'OOO "REDMI GROUP"',
            'legal_name_prefix' => 'LLC',
            'token' => 'token-oppo',
            'logo_url' => false,
            'maintainer_id' => 10,
            'current_sales' => 30000000,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'company_id' => 10,
        ];

        DB::table('merchants')->insert([
            $merchant_1,
            $merchant_2,
            $merchant_3,
            $merchant_4,
            $merchant_5,
            $merchant_6,
            $merchant_7,
            $merchant_8,
            $merchant_9,
            $merchant_10,
        ]);
    }
}
