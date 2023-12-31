<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiMerchantGateway\Notifications;

use App\DTOs\Auth\AzoAccessDto;
use App\Filters\CommonFilters\CreatedAtFilter;
use App\Filters\Notification\FreshFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiMerchantGateway\Notifications\NotificationsResource;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;

class NotificationsController extends Controller
{
//    public function __construct(
//        private AzoAccessDto $azoAccessDto
//    ) {
//    }

    public function index(Request $request, AzoAccessDto $azoAccessDto): JsonResource
    {
        $notifications = Notification::query()
            ->filterRequest($request, [
                FreshFilter::class,
                CreatedAtFilter::class,

            ])
            ->latest()
            ->onlyByStore($azoAccessDto->getStoreId())
            ->OnlyMoreThanStartSchedule()
            ->latest();

        if ($request->query('object') == true) {
            return new NotificationsResource($notifications->first());
        }

        return NotificationsResource::collection($notifications->paginate($request->query('per_page') ?? 15));
    }

    public function getCounter(AzoAccessDto $azoAccessDto): array
    {
        $notifications = Cache::tags('notifications')->remember('fresh_notifications_by_store_' . $azoAccessDto->getStoreId(), 5 * 60, function () use ($azoAccessDto) {
            return Notification::query()
                ->onlyByStore($azoAccessDto->getStoreId())
                ->where('start_schedule', '<=', now())
                ->where('end_schedule', '>=', now())->count();
        });

        return [
            'count' => $notifications,
        ];
    }
}
