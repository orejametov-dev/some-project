<?php

namespace App\Console\Commands\Settings;

use App\HttpRepositories\Prm\CompanyHttpRepository;
use App\HttpServices\Core\CoreService;
use App\Modules\Merchants\Models\ActivityReason;
use App\Modules\Merchants\Models\Merchant;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Log;

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
    public function handle(CoreService $coreService, CompanyHttpRepository $companyHttpRepository)
    {
        Log::channel('command')->info(self::class . '|' . now() . ':' . 'started');

        $from_date = Carbon::now()->subWeeks(2)->format('Y-m-d');
        $to_date = Carbon::now()->format('Y-m-d');

        Merchant::query()->where('active', true)
            ->where('created_at', '<', $from_date)
            ->chunkById(100, function ($merchants) use ($companyHttpRepository, $coreService, $from_date, $to_date) {
                foreach ($merchants as $merchant) {
                    $activity_reasons = DB::table('merchant_activities')
                        ->where('merchant_id', $merchant->id)
                        ->orderByDesc('id')
                        ->first();

                    if ($activity_reasons !== null && $activity_reasons->active === 1 && $activity_reasons->created_at > $from_date) {
                        continue;
                    }

                    $result = $coreService->getMerchantApplicationsAndClientsCountByRange($merchant->id, $from_date, $to_date);
                    $resultData = $result['data'];
                    if ($resultData['applications_count'] == 0 && $resultData['clients_count'] == 0) {
                        $merchant->active = false;
                        $merchant->save();

                        $merchant->activity_reasons()->attach(ActivityReason::MERCHANT_AUTO_DEACTIVATION_REASON_ID, [
                            'active' => $merchant->active,
                        ]);

                        $companyHttpRepository->setStatusNotActive($merchant->id);

                        Cache::tags($merchant->id)->flush();
                        Cache::tags('merchants')->flush();
                    }
                }
            });
        Log::channel('command')->info(self::class . '|' . now() . ':' . 'finished');

        return 0;
    }
}
