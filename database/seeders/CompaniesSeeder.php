<?php

namespace Database\Seeders;

use App\Modules\Merchants\Models\Merchant;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompaniesSeeder extends Seeder
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
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $merchant_2 = [
            'id' => 2,
            'name' => 'Arena Markaz',
            'legal_name' => 'ООО «RETAIL OPERATION GROUP»',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $merchant_3 = [
            'id' => 3,
            'name' => 'Mobilezone',
            'legal_name' => 'OOO "Mobilezone Store"',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $merchant_4 = [
            'id' => 4,
            'name' => 'GSHOP',
            'legal_name' => 'OOO "GSHOP"',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $merchant_5 = [
            'id' => 5,
            'name' => 'DEADSHOT',
            'legal_name' => 'OOO "DEADSHOT"',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $merchant_6 = [
            'id' => 6,
            'name' => 'Mega-shop',
            'legal_name' => 'OOO "League group"',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $merchant_7 = [
            'id' => 7,
            'name' => 'Rolton',
            'legal_name' => 'OOO "ROLTON"',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $merchant_8 = [
            'id' => 8,
            'name' => 'Korzinka.uz',
            'legal_name' => 'OOO "REDTAG GROUP"',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $merchant_9 = [
            'id' => 9,
            'name' => 'Huawei Store',
            'legal_name' => 'OOO "HUAWEI"',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        $merchant_10 = [
            'id' => 10,
            'name' => 'Oppo',
            'legal_name' => 'OOO "REDMI GROUP"',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];

        DB::table('companies')->insert([
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