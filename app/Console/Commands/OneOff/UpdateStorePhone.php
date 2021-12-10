<?php

namespace App\Console\Commands\OneOff;

use App\HttpServices\Storage\StorageMicroService;
use App\Modules\Merchants\Models\AzoMerchantAccess;
use App\Modules\Merchants\Models\Store;
use Illuminate\Console\Command;
use function Clue\StreamFilter\fun;

class UpdateStorePhone extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:store_phone';

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
        Store::query()->chunkById(100, function ($stores) {
            foreach ($stores as $store) {
               if (empty($store->phone)) {
                   $merchant_access = AzoMerchantAccess::query()
                       ->where('store_id' , $store->id)->first();

                   $store->update(['phone' => $merchant_access->phone]);
               }
            }
        });
    }
}
