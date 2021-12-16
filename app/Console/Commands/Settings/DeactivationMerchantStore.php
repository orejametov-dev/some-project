<?php

namespace App\Console\Commands\Settings;

use App\Modules\Merchants\Models\ActivityReason;
use App\Modules\Merchants\Models\Merchant;
use App\Modules\Merchants\Models\Store;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\HttpServices\Core\CoreService;
use Illuminate\Support\Facades\Cache;

class DeactivationMerchantStore extends Command
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
    public function handle(CoreService $coreService)
    {
        $from_date = Carbon::now()->subWeeks(2)->format('Y-m-d');;
        $to_date = Carbon::now()->format('Y-m-d');

        Merchant::where('active' , true)
            ->chunkById(100, function ($merchants) use ($coreService, $from_date, $to_date) {
                foreach ($merchants as $merchant)
                {
                    $result = $coreService->getMerchantApplicationsAndClientsCountByRange($merchant->id,$from_date, $to_date);
                    $resultData = $result['data'];
                    if($resultData['applications_count'] == 0 && $resultData['clients_count'] == 0)
                    {
                        $merchant->active = false;
                        $merchant->save();

                        $merchant->activity_reasons()->attach(ActivityReason::MERCHANT_AUTO_DEACTIVATION_REASON_ID, [
                            'active' => $merchant->active,
                        ]);

                        Cache::tags($merchant->id)->flush();
                        Cache::tags('merchants')->flush();
                    }
                }
            });
        \Log::info(DeactivationMerchantStore::class . "|" . now());
    }
}
