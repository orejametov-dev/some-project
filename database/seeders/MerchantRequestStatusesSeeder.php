<?php

namespace Database\Seeders;

use App\Modules\Merchants\Services\RequestStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MerchantRequestStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            [
                'name' => 'Новый',
                'order' => 1
            ],
            [
                'name' => 'Рассматривается',
                'order' => 2
            ],
            [
                'name' => 'Одобрено',
                'order' => 3
            ],
            [
                'name' => 'Корзина',
                'order' => 4
            ],
        ];

        DB::table('merchant_request_statuses')->insert($statuses);
    }
}
