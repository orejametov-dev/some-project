<?php

namespace App\Console\Commands\OneOff;

use App\Modules\Merchants\Models\Store;
use Illuminate\Console\Command;

class SetResponsiblePerson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set:responsible_person';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $stores = Store::chunkById(100, function ($stores) {
            foreach ($stores as $store) {
                if(!$store->responsible_person) {
                    $merchant = $store->merchant;
                    $main_store = $merchant->stores()->main()->first();
                    $merchant->stores()->whereNull('responsible_person')->update([
                        'responsible_person' => $main_store->responsible_person,
                        'responsible_person_phone' => $main_store->responsible_person_phone
                    ]);
                }
            }
        });
    }
}
