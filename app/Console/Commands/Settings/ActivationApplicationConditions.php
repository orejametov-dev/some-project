<?php

namespace App\Console\Commands\Settings;

use App\Modules\Merchants\Models\Condition;
use App\Modules\Merchants\Models\Merchant;
use App\Modules\Merchants\Services\MerchantStatus;
use Carbon\Carbon;
use Illuminate\Console\Command;
use function Clue\StreamFilter\fun;

class ActivationApplicationConditions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:activation_condition';

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
        $to_date = Carbon::now()->format('Y-m-d');

        Condition::query()
            ->with('merchant')
            ->whereHas('merchant', function ($query) {
                $query->where('active', true);
            })
            ->where('started_at', $to_date)
            ->update(['active' => true]);

        return 0;
    }
}
