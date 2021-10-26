<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlifshopMerchantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $alifshop_merchant_1 = [
            'id' => 1,
            'company_id' => 1,
            'name' => 'Idea Store',
            'legal_name' => 'OOO "Idea"',
            'information' => 'Lorem ipsum dolar set',
            'token' => 'token-idea',
            'alifshop_slug' => 'idea',
            'logo_url' => false, // new options
            'maintainer_id' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $alifshop_merchant_2 = [
            'id' => 2,
            'company_id' => 2 ,
            'name' => 'Arena Markaz',
            'legal_name' => 'ООО «RETAIL OPERATION GROUP»',
            'token' => 'token-arenamarkaz',
            'alifshop_slug' => 'arena_markaz',
            'information' => 'Lorem ipsum dolar set',
            'logo_url' => false,
            'maintainer_id' => 2,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $alifshop_merchant_3 = [
            'id' => 3,
            'company_id' => 3,
            'name' => 'Mobilezone',
            'legal_name' => 'OOO "Mobilezone Store"',
            'token' => 'token-mobilezone',
            'alifshop_slug' => 'mobilezone',
            'information' => 'Lorem ipsum dolar set',
            'logo_url' => false,
            'maintainer_id' => 3,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $alifshop_merchant_4 = [
            'id' => 4,
            'company_id' => 4,
            'name' => 'GSHOP',
            'legal_name' => 'OOO "GSHOP"',
            'token' => 'token-gshop',
            'alifshop_slug' => 'gshop',
            'information' => 'Lorem ipsum dolar set',
            'logo_url' => false,
            'maintainer_id' => 4,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $alifshop_merchant_5 = [
            'id' => 5,
            'company_id' => 5 ,
            'name' => 'DEADSHOT',
            'legal_name' => 'OOO "DEADSHOT"',
            'token' => 'token-deadshot',
            'alifshop_slug' => 'deadshot',
            'information' => 'Lorem ipsum dolar set',
            'logo_url' => false,
            'maintainer_id' => 5,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $alifshop_merchant_6 = [
            'id' => 6,
            'company_id' => 6,
            'name' => 'Mega-shop',
            'legal_name' => 'OOO "League group"',
            'token' => 'token-mega-shop',
            'alifshop_slug' => 'mega-shop',
            'information' => 'Lorem ipsum dolar set',
            'logo_url' => false,
            'maintainer_id' => 6,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $alifshop_merchant_7 = [
            'id' => 7,
            'company_id' => 7,
            'name' => 'Rolton',
            'legal_name' => 'OOO "ROLTON"',
            'token' => 'token-rolton',
            'alifshop_slug' => 'rolton',
            'information' => 'Lorem ipsum dolar set',
            'logo_url' => false,
            'maintainer_id' => 7,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $alifshop_merchant_8 = [
            'id' => 8,
            'comany_id' => 8,
            'name' => 'Korzinka.uz',
            'legal_name' => 'OOO "REDTAG GROUP"',
            'token' => 'token-korzinkauz',
            'alifshop_slug' => 'korzinka.uz',
            'information' => 'Lorem ipsum dolar set',
            'logo_url' => false,
            'maintainer_id' => 8,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $alifshop_merchant_9 = [
            'id' => 9,
            'company_id' => 9,
            'name' => 'Huawei Store',
            'legal_name' => 'OOO "HUAWEI"',
            'token' => 'token-huaweistore',
            'alifshop_slug' => 'huawei-store',
            'information' => 'Lorem ipsum dolar set',
            'logo_url' => false,
            'maintainer_id' => 9,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $alifshop_merchant_10 = [
            'id' => 10,
            'company_id' => 10,
            'name' => 'Oppo',
            'legal_name' => 'OOO "REDMI GROUP"',
            'token' => 'token-oppo',
            'alifshop_slug' => 'oppo',
            'information' => 'Lorem ipsum dolar set',
            'logo_url' => false,
            'maintainer_id' => 10,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        DB::table('alifshop_merchants')->insert([
            $alifshop_merchant_1,
            $alifshop_merchant_2,
            $alifshop_merchant_3,
            $alifshop_merchant_4,
            $alifshop_merchant_5,
            $alifshop_merchant_6,
            $alifshop_merchant_7,
            $alifshop_merchant_8,
            $alifshop_merchant_9,
            $alifshop_merchant_10
        ]);
    }
}
