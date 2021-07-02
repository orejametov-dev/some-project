<?php

namespace App\Console\Commands\OneOff;

use App\Modules\Merchants\Models\Condition;
use Illuminate\Console\Command;

class Active50Conditions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set:conditions_active';

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
        $conditions = Condition::query()
            ->active()
            ->where('commission', 47)
            ->where('duration', 15)
            ->get();

        foreach ($conditions as $condition)
        {
            $special_cond = Condition::where('merchant_id', $condition->merchant_id)
                ->where('active', false)
                ->where('commission', 50)->where('duration', 15)->first();

            if($special_cond) {
                $special_cond->active = true;
                $special_cond->save();
            } else {
                $new_cond = new Condition([
                    'commission' => 50,
                    'duration' => 15,
                    'active' => true
                ]);
                $new_cond->merchant_id = $condition->merchant_id;
                $new_cond->store_id = $condition->store_id;
                $new_cond->save();
            }

            $condition->update(['active' => false]);
        }
    }
}
