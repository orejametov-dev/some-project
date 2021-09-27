<?php

namespace App\Console\Commands\OneOff;

use App\Modules\Merchants\Models\Condition;
use Illuminate\Console\Command;

class ChangePostMerchantToTrue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set:post_merchant_true';

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
        $conditions = Condition::where('active', true)->get();

        foreach ($conditions as $condition)
        {
            $condition->post_merchant = true;
            $condition->save();
        }
    }
}
