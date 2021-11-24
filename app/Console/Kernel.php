<?php

namespace App\Console;

use App\Console\Commands\Settings\DeactivationMerchantStore;
use App\Console\Commands\Settings\UpdateCurrentSales;
use App\Console\Commands\Settings\WriteLogsToTable;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(UpdateCurrentSales::class)->dailyAt('00:00');
        $schedule->command(DeactivationMerchantStore::class)->dailyAt('01:00');
        $schedule->command(WriteLogsToTable::class)->everyTenMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
