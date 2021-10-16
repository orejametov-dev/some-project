<?php

namespace Database\Seeders;

use App\Modules\Merchants\Models\Merchant;
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
            'information' => 'Lorem ipsum dolar set',
            'token' => 'token-idea',
            'alifshop_slug' => 'idea',
            'telegram_chat_id' => 1,
            'logo_url' => false, // new options
            'maintainer_id' => 1,
            'current_sales' => 1000000,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'company_id' => 1
        ];

        $merchant_2 = [
            'id' => 2,
            'name' => 'Arena Markaz',
            'legal_name' => 'ООО «RETAIL OPERATION GROUP»',
            'token' => 'token-arenamarkaz',
            'alifshop_slug' => 'arena_markaz',
            'information' => 'Lorem ipsum dolar set',
            'telegram_chat_id' => 2,
            'logo_url' => false,
            'maintainer_id' => 2,
            'current_sales' => 2000000,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'company_id' => 2
        ];

        $merchant_3 = [
            'id' => 3,
            'name' => 'Mobilezone',
            'legal_name' => 'OOO "Mobilezone Store"',
            'token' => 'token-mobilezone',
            'alifshop_slug' => 'mobilezone',
            'information' => 'Lorem ipsum dolar set',
            'telegram_chat_id' => 3,
            'logo_url' => false,
            'maintainer_id' => 3,
            'current_sales' => 3000000,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'company_id' => 3
        ];

        $merchant_4 = [
            'id' => 4,
            'name' => 'GSHOP',
            'legal_name' => 'OOO "GSHOP"',
            'token' => 'token-gshop',
            'alifshop_slug' => 'gshop',
            'information' => 'Lorem ipsum dolar set',
            'telegram_chat_id' => 4,
            'logo_url' => false,
            'maintainer_id' => 4,
            'current_sales' => 3000000,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'company_id' => 4
        ];

        $merchant_5 = [
            'id' => 5,
            'name' => 'DEADSHOT',
            'legal_name' => 'OOO "DEADSHOT"',
            'token' => 'token-deadshot',
            'alifshop_slug' => 'deadshot',
            'information' => 'Lorem ipsum dolar set',
            'telegram_chat_id' => 5,
            'logo_url' => false,
            'maintainer_id' => 5,
            'current_sales' => 50000000,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'company_id' => 5
        ];

        $merchant_6 = [
            'id' => 6,
            'name' => 'Mega-shop',
            'legal_name' => 'OOO "League group"',
            'token' => 'token-mega-shop',
            'alifshop_slug' => 'mega-shop',
            'information' => 'Lorem ipsum dolar set',
            'telegram_chat_id' => 6,
            'logo_url' => false,
            'maintainer_id' => 6,
            'current_sales' => 20000000,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'company_id' => 6
        ];

        $merchant_7 = [
            'id' => 7,
            'name' => 'Rolton',
            'legal_name' => 'OOO "ROLTON"',
            'token' => 'token-rolton',
            'alifshop_slug' => 'rolton',
            'information' => 'Lorem ipsum dolar set',
            'telegram_chat_id' => 7,
            'logo_url' => false,
            'maintainer_id' => 7,
            'current_sales' => 30000000,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'company_id' => 7
        ];

        $merchant_8 = [
            'id' => 8,
            'name' => 'Korzinka.uz',
            'legal_name' => 'OOO "REDTAG GROUP"',
            'token' => 'token-korzinkauz',
            'alifshop_slug' => 'korzinka.uz',
            'information' => 'Lorem ipsum dolar set',
            'telegram_chat_id' => 8,
            'logo_url' => false,
            'maintainer_id' => 8,
            'current_sales' => 100000000,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'company_id' => 8
        ];

        $merchant_9 = [
            'id' => 9,
            'name' => 'Huawei Store',
            'legal_name' => 'OOO "HUAWEI"',
            'token' => 'token-huaweistore',
            'alifshop_slug' => 'huawei-store',
            'information' => 'Lorem ipsum dolar set',
            'telegram_chat_id' => 9,
            'logo_url' => false,
            'maintainer_id' => 9,
            'current_sales' => 500000000,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'company_id' => 9
        ];

        $merchant_10 = [
            'id' => 10,
            'name' => 'Oppo',
            'legal_name' => 'OOO "REDMI GROUP"',
            'token' => 'token-oppo',
            'alifshop_slug' => 'oppo',
            'information' => 'Lorem ipsum dolar set',
            'telegram_chat_id' => 10,
            'logo_url' => false,
            'maintainer_id' => 10,
            'current_sales' => 30000000,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'company_id' => 10
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
