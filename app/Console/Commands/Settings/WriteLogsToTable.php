<?php

declare(strict_types=1);

namespace App\Console\Commands\Settings;

use App\UseCases\Logs\WriteLogsToMongoUseCase;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(WriteLogsToMongoUseCase $writeLogsToMongoUseCase)
    {
        Log::channel('command')->info(self::class . '|' . now() . ':' . 'started');

        $writeLogsToMongoUseCase->execute();

        Log::channel('command')->info(self::class . '|' . now() . ':' . 'finished');

        return 0;
    }
}
