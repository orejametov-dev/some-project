<?php

namespace Database\Seeders;

use App\Modules\Merchants\Models\Merchant;
use Illuminate\Database\Seeder;

class MerchantsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $merchants = [
            [
                'name' => 'Crediton',
                'legal_name' => 'OOO "Crediton"',
                'information' => 'Lorem ipsum dolar set',
                'token' => 'qzkflpugv68i03dtq9h44p4stesk7h9nf2wkcwwlcj',
                'alifshop_slug' => 'crediton',
                'telegram_chat_id' => '111111',
                'has_deliveries' => 1,
                'has_manager' => 1,
                'has_applications' => 1,
                'has_orders' => 1,
                'paymo_terminal' => 1,
                'maintainer_id' => 1,
                'current_sales' => 1000000,
            ],
            [
                'name' => 'Арена Марказ',
                'legal_name' => 'ООО «RETAIL OPERATION GROUP»',
                'token' => '0efdfd8dac7be7620e686bc340e020b3',
                'alifshop_slug' => 'arena-markaz',
                'information' => 'Lorem ipsum dolar set',
                'telegram_chat_id' => '222222',
                'has_deliveries' => 1,
                'has_manager' => 1,
                'has_applications' => 1,
                'has_orders' => 1,
                'paymo_terminal_id' => 2,
                'maintainer_id' => 2,
                'current_sales' => 2000000,
            ],
            [
                'name' => 'Idea Store',
                'legal_name' => 'OOO "Idea Store"',
                'token' => 't56ubiudt6lhn3prmk872qqf5rcfabkzvc7dy5cc9q',
                'alifshop_slug' => 'idea-store',
                'information' => 'Lorem ipsum dolar set',
                'telegram_chat_id' => '333333',
                'has_deliveries' => 1,
                'has_manager' => 1,
                'has_applications' => 1,
                'has_orders' => 1,
                'paymo_terminal_id' => 3,
                'maintainer_id' => 3,
                'current_sales' => 3000000,
            ]
        ];

        Merchant::query()->insert($merchants);
    }
}
