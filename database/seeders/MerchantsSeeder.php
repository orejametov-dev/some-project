<?php

namespace Database\Seeders;

use App\Modules\Merchants\Models\Merchant;
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
            'has_deliveries' => 1,
            'has_manager' => 1,
            'has_applications' => 1,
            'maintainer_id' => 1,
            'current_sales' => 1000000,
        ];

        $merchant_2 = [
            'id' => 2,
            'name' => 'Arena Markaz',
            'legal_name' => 'ООО «RETAIL OPERATION GROUP»',
            'token' => 'token-arenamarkaz',
            'alifshop_slug' => 'arena_markaz',
            'information' => 'Lorem ipsum dolar set',
            'telegram_chat_id' => 2,
            'has_deliveries' => 1,
            'has_manager' => 1,
            'has_applications' => 1,
            'maintainer_id' => 2,
            'current_sales' => 2000000,
        ];

        $merchant_3 = [
            'id' => 3,
            'name' => 'Mobilezone',
            'legal_name' => 'OOO "Mobilezone Store"',
            'token' => 'token-mobilezone',
            'alifshop_slug' => 'mobilezone',
            'information' => 'Lorem ipsum dolar set',
            'telegram_chat_id' => 3,
            'has_deliveries' => 1,
            'has_manager' => 1,
            'has_applications' => 1,
            'maintainer_id' => 3,
            'current_sales' => 3000000,
        ];

        $merchant_4 = [
            'id' => 4,
            'name' => 'GSHOP',
            'legal_name' => 'OOO "GSHOP"',
            'token' => 'token-gshop',
            'alifshop_slug' => 'gshop',
            'information' => 'Lorem ipsum dolar set',
            'telegram_chat_id' => 4,
            'has_deliveries' => 0,
            'has_manager' => 1,
            'has_applications' => 0,
            'maintainer_id' => 4,
            'current_sales' => 3000000,
        ];

        DB::table('merchants')->insert([
            $merchant_1,
            $merchant_2,
            $merchant_3,
            $merchant_4,
        ]);
    }
}
