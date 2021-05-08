<?php

namespace App\Console\Commands\OneOff;

use App\Modules\Merchants\Models\Merchant;
use Illuminate\Console\Command;

class DeactivateMerchantCondition extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'merchant_condition:deactivate {id*} {--from=} {--to=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Команда деактивирует активное условие у мерчантов с условием 50%';

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
        $this->info('Creating new condition for merchants...');
        $this->newLine(2);
        $ids = (array)$this->argument('id');

        $query = Merchant::query();
        if (count($ids) === 1 && $ids[0] === 'all') {
            if (($from = $this->option('from')) && ($to = $this->option('to'))) {
                $query->whereBetween('id', [$from, $to]);
            }
        } else {
            $query->whereIn('id', $ids);
        }

        $count = $query->count();
        $progressBar = $this->output->createProgressBar($count);
        $progressBar->start();

        $query->chunk(100, function ($merchants) use ($progressBar) {
            foreach ($merchants as $merchant) {
                if (! in_array($merchant->id, [3, 13])) {
                    $conditions = $merchant->application_conditions;

                    foreach ($conditions as $condition) {
                        if ($condition->duration == 15) {
                            $condition->active = false;
                            $condition->save();
                        }
                    }
                }

                $progressBar->advance();
            }

            $progressBar->finish();
            $this->newLine(2);
            $this->info('Completed!');
        });

        return 0;
    }
}
