<?php

declare(strict_types=1);

namespace App\Console\Commands\Settings;

use App\UseCases\Merchants\UpdateMerchantCurrentSalesUseCase;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateCurrentSales extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:current_sales';

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
    public function handle(UpdateMerchantCurrentSalesUseCase $updateMerchantCurrentSalesUseCase)
    {
        Log::channel('command')->info(self::class . '|' . now() . ':' . 'started');

        $updateMerchantCurrentSalesUseCase->execute();

        Log::channel('command')->info(self::class . '|' . now() . ':' . 'finished');

        return 0;
    }
}
