<?php

namespace App\Console\Commands\Settings;

use App\Services\CacheService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;


class WriteLogsToTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:writing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Writing logs to table logs every ten minutes';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    private $logs;

    public function __construct()
    {
        parent::__construct();
        $this->logs = Cache::tags(CacheService::LOGS)->get('logs_');
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (!empty($this->logs)) {
            DB::connection('logs')->table('logs')->insert($this->logs);
            Cache::tags(CacheService::LOGS)->flush();
        }
    }
}
