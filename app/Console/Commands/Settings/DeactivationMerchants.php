<?php

namespace App\Console\Commands\Settings;

use App\UseCases\Merchants\MassDeactivationMerchantsUseCase;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DeactivationMerchants extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:deactivation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command deactivation Merchant and Store';

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
    public function handle(MassDeactivationMerchantsUseCase $massDeactivationMerchantsUseCase)
    {
        Log::channel('command')->info(self::class . '|' . now() . ':' . 'started');

        $massDeactivationMerchantsUseCase->execute();

        Log::channel('command')->info(self::class . '|' . now() . ':' . 'finished');

        return 0;
    }
}
