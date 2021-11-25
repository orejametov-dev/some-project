<?php

namespace App\Console\Commands\Settings;

use App\Services\TimeLogger;
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
        $this->logs = Cache::get(TimeLogger::CACHE_KEY);
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            if (!empty($this->logs)) {
                foreach (array_chunk($this->logs, 10000) as $chunk_logs) {
                    DB::connection('logs')
                        ->table('logs')
                        ->insert($chunk_logs);
                }

                Cache::forget(TimeLogger::CACHE_KEY);
            }
        } catch (\Exception $e) {
            if (app()->bound('sentry')) {
                app('sentry')->captureException($e);
            }

            Cache::forget(TimeLogger::CACHE_KEY);
        }
    }


}
