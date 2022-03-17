<?php

declare(strict_types=1);

namespace App\UseCases\Merchants;

use App\HttpRepositories\Core\CoreHttpRepository;
use App\HttpRepositories\Prm\CompanyHttpRepository;
use App\Modules\Merchants\Models\ActivityReason;
use App\Modules\Merchants\Models\Merchant;
use App\UseCases\Cache\FlushCacheUseCase;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MassDeactivationMerchantsUseCase
{
    public function __construct(
        private CoreHttpRepository $coreHttpService,
        private CompanyHttpRepository $companyHttpRepository,
        private FlushCacheUseCase $flushCacheUseCase,
    ) {
    }

    public function execute(): void
    {
        $from_date = Carbon::now()->subWeeks(2);
        $to_date = Carbon::now();

        Merchant::query()->where('active', true)
            ->where('created_at', '<', $from_date)
            ->chunkById(100, function ($merchants) use ($from_date, $to_date) {
                foreach ($merchants as $merchant) {
                    $activity_reasons = DB::table('merchant_activities')
                        ->where('merchant_id', $merchant->id)
                        ->orderByDesc('id')
                        ->first();

                    if ($activity_reasons !== null && $activity_reasons->active === 1 && $activity_reasons->created_at > $from_date) {
                        continue;
                    }

                    $result = $this->coreHttpService->getMerchantApplicationsAndClientsCountByRange((int) $merchant->id, $from_date, $to_date);
                    if ($result->applications_count === 0 && $result->clients_count === 0) {
                        $merchant->active = false;
                        $merchant->save();

                        $merchant->activity_reasons()->attach(ActivityReason::MERCHANT_AUTO_DEACTIVATION_REASON_ID, [
                            'active' => $merchant->active,
                        ]);

                        $this->companyHttpRepository->setStatusNotActive($merchant->id);

                        $this->flushCacheUseCase->execute($merchant->id);
                    }
                }
            });
    }
}
