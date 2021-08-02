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
            'current_sales' => 1000000,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $merchant_2 = [
            'id' => 2,
            'name' => 'Arena Markaz',
            'legal_name' => 'ООО «RETAIL OPERATION GROUP»',
            'token' => 'token-arenamarkaz',
            'alifshop_slug' => 'arena_markaz',
            'information' => 'Lorem ipsum dolar set',
            'telegram_chat_id' => 2,
            'maintainer_id' => 2,
            'current_sales' => 2000000,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $merchant_3 = [
            'id' => 3,
            'name' => 'Mobilezone',
            'legal_name' => 'OOO "Mobilezone Store"',
            'token' => 'token-mobilezone',
            'alifshop_slug' => 'mobilezone',
            'information' => 'Lorem ipsum dolar set',
            'telegram_chat_id' => 3,
            'maintainer_id' => 3,
            'current_sales' => 3000000,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $merchant_4 = [
            'id' => 4,
            'name' => 'GSHOP',
            'legal_name' => 'OOO "GSHOP"',
            'token' => 'token-gshop',
            'alifshop_slug' => 'gshop',
            'information' => 'Lorem ipsum dolar set',
            'telegram_chat_id' => 4,
            'maintainer_id' => 4,
            'current_sales' => 3000000,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        DB::table('merchants')->insert([
            $merchant_1,
            $merchant_2,
            $merchant_3,
            $merchant_4,
        ]);
    }
}
