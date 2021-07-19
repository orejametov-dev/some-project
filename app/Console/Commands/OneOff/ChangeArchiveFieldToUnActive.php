<?php

namespace App\Console\Commands\OneOff;

use App\Modules\Merchants\Models\Merchant;
use App\Modules\Merchants\Models\Store;
use Illuminate\Console\Command;

class ChangeArchiveFieldToUnActive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set:active';

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
        $merchants = Merchant::where('status_id', 2)->get();

        foreach ($merchants as $merchant)
        {
            $merchant->active = false;
            $merchant->save();
        }

        $stores = Store::where('is_archived', true)->get();

        foreach ($stores as $store)
        {
            $store->active = false;
            $store->save();
        }
    }
}
