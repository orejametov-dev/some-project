<?php

namespace App\Console\Commands\OneOff;

use App\Modules\Merchants\Models\Condition;
use App\Modules\Merchants\Models\Merchant;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;

class ActivateMerchantCondition extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'merchant_condition:activate {id*} {--from=} {--to=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Команда создает и активирует новое условие для мерчантов';

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

        $query = Merchant::query()
            ->whereNotIn('id', [3, 13])
            ->with('stores', function ($q) {
                $q->where('is_main', 1);
            });

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
                $main_store = $merchant->stores->first();
                if (!$main_store) {
                    continue;
                }

                $new_condition = new Condition();
                $new_condition->duration = 1;
                $new_condition->commission = 7;
                $new_condition->discount = 0;
                $new_condition->active = 1;
                $new_condition->merchant_id = $merchant->id;
                $new_condition->store_id = $main_store->id;
                $new_condition->save();

                $new_condition = new Condition();
                $new_condition->duration = 3;
                $new_condition->commission = 15;
                $new_condition->discount = 0;
                $new_condition->active = 1;
                $new_condition->merchant_id = $merchant->id;
                $new_condition->store_id = $main_store->id;
                $new_condition->save();

                $progressBar->advance();
            }

        });

        $progressBar->finish();
        $this->newLine(2);

        return 0;
    }
}
