<?php

namespace App\Console\Commands\OneOff;

use App\Modules\Merchants\Models\Condition;
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
        $this->info('Deactivating conditions on merchants...');
        $this->newLine(2);
        $ids = (array)$this->argument('id');

        $query = Condition::query()
            ->where('duration', 15)
            ->where('commission', 50)
            ->active();

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

        $query->chunk(100, function ($conditions) use ($progressBar) {
            foreach ($conditions as $condition) {
                $condition->active = false;
                $condition->save();
            }

            $progressBar->advance();
        });

        $progressBar->finish();
        $this->newLine(2);
        $this->info('Completed!');

        return 0;
    }
}
