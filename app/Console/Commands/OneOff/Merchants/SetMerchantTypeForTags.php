<?php

namespace App\Console\Commands\OneOff\Merchants;

use App\Modules\Merchants\Models\Merchant;
use Illuminate\Console\Command;

class SetMerchantTypeForTags extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set:merchant_type';

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
        \DB::table('merchant_tag')->update([
            'merchant_type' => Merchant::class
        ]);
    }
}
