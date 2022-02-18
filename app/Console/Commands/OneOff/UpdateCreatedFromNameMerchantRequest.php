<?php

namespace App\Console\Commands\OneOff;

use App\Modules\Merchants\Models\Request;
use Illuminate\Console\Command;

class UpdateCreatedFromNameMerchantRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:created_from_name';

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
        Request::query()->update(['created_from_name' => 'MERCHANT']);
    }
}
