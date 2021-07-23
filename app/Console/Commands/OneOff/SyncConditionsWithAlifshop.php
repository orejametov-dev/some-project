<?php

namespace App\Console\Commands\OneOff;

use App\Modules\Merchants\Models\Merchant;
use App\Services\Alifshop\AlifshopService;
use Illuminate\Console\Command;

class SyncConditionsWithAlifshop extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:conditions';

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
        $merchants = Merchant::query()->whereIn('id', [
            224, 213, 212, 210, 208, 207, 200, 198, 196, 192, 187, 183, 161, 139, 130, 126, 93, 11
        ])->get();

        foreach ($merchants as $merchant)
        {
            $merchant->load(['application_conditions' => function ($q) {
                $q->active();
            }]);

            $conditions = $merchant->application_conditions->map(function ($item) {
                return [
                    'commission' => $item->commission,
                    'duration' => $item->duration,
                    'is_active' => $item->active,
                    'special_offer' => $item->special_offer
                ];
            });

            $alifshopService = new AlifshopService;
            $alifshopService->storeOrUpdateMerchant($merchant, $conditions);
        }

        return 0;
    }
}
