<?php

namespace App\Console\Commands\OneOff;

use App\HttpServices\Storage\StorageMicroService;
use App\Modules\Merchants\Models\AzoMerchantAccess;
use App\Modules\Merchants\Models\Merchant;
use App\Modules\Merchants\Models\Request;
use App\Modules\Merchants\Models\Store;
use Carbon\Carbon;
use Illuminate\Console\Command;
use function Clue\StreamFilter\fun;

class MoveOldInProcessRequestsToTrash extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'request:move_to_trash';

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
        Request::whereDate('created_at', '<=', Carbon::parse('2021-09-21'))
            ->where('status_id', Request::IN_PROCESS)
            ->update(['status_id' => Request::TRASH]);
    }
}
