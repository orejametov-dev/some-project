<?php

namespace Database\Seeders;

use App\Modules\Core\Models\WebService;
use Illuminate\Database\Seeder;

class WebServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $web_services = [
            [
                'name' => 'MERCHANT',
                'token' => 'service-token-merchant',
                'note' => 'some note text',
            ],
            [
                'name' => 'ONLINE',
                'token' => 'sservice-token-online',
                'note' => 'some note text',
            ],
            [
                'name' => 'MERCHANT_ADMIN',
                'token' => 'service-token-merchant-admin',
                'note' => 'some note text',
            ],
            [
                'name' => 'BILLING',
                'token' => 'service-token-billing',
                'note' => 'some note text',
            ],
            [
                'name' => 'COMPLIANCE',
                'token' => 'service-token-compliance',
                'note' => 'some note text',
            ],
            [
                'name' => 'ALIFSHOP',
                'token' => 'service-token-alifshop',
                'note' => 'some note text',
            ],
            [
                'name' => 'CALLS',
                'token' => 'service-token-calls',
                'note' => 'some note text',
            ],
            [
                'name' => 'CRM',
                'token' => 'service-token-crm',
                'note' => 'some note text',
            ],
            [
                'name' => 'LAW',
                'token' => 'service-token-law',
                'note' => 'some note text',
            ],
            [
                'name' => 'CREDITON',
                'token' => 'service-token-crediton',
                'note' => 'some note text',
            ],
            [
                'name' => 'STAKE',
                'token' => 'service-token-stake',
                'note' => 'some note text',
            ],
            [
                'name' => 'E-COMMERCE',
                'token' => 'service-token-e-commerce',
                'note' => 'some note text',
            ],
            [
                'name' => 'REPORT',
                'token' => 'service-token-report',
                'note' => 'some note text',
            ],
            [
                'name' => 'PRM',
                'token' => 'service-token-prm',
                'note' => 'some note text',
            ],
            [
                'name' => 'CREDIT',
                'token' => 'service-token-credits',
                'note' => 'some note text',
            ],
        ];

        WebService::query()->insert($web_services);
    }
}
