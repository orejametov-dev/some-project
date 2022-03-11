<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiMerchantGateway\Notifications;

use App\Filters\CommonFilters\CreatedAtFilter;
use App\Filters\Notification\FreshFilter;
use App\Http\Controllers\ApiMerchantGateway\ApiBaseController;
use App\Http\Resources\ApiMerchantGateway\Notifications\NotificationsResource;
use App\Modules\Merchants\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class NotificationsController extends ApiBaseController
{
    public function index(Request $request)
    {
        $notifications = Notification::query()
            ->filterRequest($request, [
                FreshFilter::class,
                CreatedAtFilter::class,

            ])
            ->latest()
            ->onlyByStore($this->store_id)
            ->OnlyMoreThanStartSchedule()
            ->latest();

        if ($request->query('object') == true) {
            return new NotificationsResource($notifications->first());
        }

        return NotificationsResource::collection($notifications->paginate($request->query('per_page') ?? 15));
    }

    public function getCounter()
    {
        $notifications = Cache::tags('notifications')->remember('fresh_notifications_by_store_' . $this->store_id, 5 * 60, function () {
            return Notification::query()
                ->onlyByStore($this->store_id)
                ->where('start_schedule', '<=', now())
                ->where('end_schedule', '>=', now())->count();
        });

        return [
            'count' => $notifications,
        ];
    }
}
