<?php

namespace App\Console\Commands\OneOff;

use App\Modules\Merchants\Models\Store;
use DB;
use Illuminate\Console\Command;

class FixPhone extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:responsible_person_phone';

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
        $stores = Store::whereNotNull('responsible_person_phone')
            ->whereRaw('char_length(responsible_person_phone) = 9')
            ->get();

        foreach ($stores as $store) {
            $store->responsible_person_phone = "998".$store->responsible_person_phone;
            $store->save();
        }
    }
}
