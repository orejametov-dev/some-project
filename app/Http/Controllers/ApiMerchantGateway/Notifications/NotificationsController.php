<?php


namespace App\Http\Controllers\ApiMerchantGateway\Notifications;


use App\Http\Controllers\ApiMerchantGateway\ApiBaseController;
use App\Http\Resources\ApiMerchantGateway\Notifications\NotificationsResource;
use App\Modules\Merchants\Models\Notification;
use Illuminate\Http\Request;

class NotificationsController extends ApiBaseController
{
    public function index(Request $request)
    {
        $notifications = Notification::query()
            ->filterRequest($request)->latest()
            ->onlyByStore($this->store_id)
            ->latest();

        if ($request->query('object') == true) {
            return new NotificationsResource($notifications->first());
        }

        if ($request->has('paginate') && $request->query('paginate') == false) {
            return NotificationsResource::collection($notifications->get());
        }

        return NotificationsResource::collection($notifications->paginate($request->query('per_page') ?? 15));
    }

    public function getCounter()
    {
        $notifications = Notification::query()
            ->onlyByStore($this->store_id)
            ->where('start_schedule', '<=', now())
            ->where('end_schedule', '>=', now())->count();

        return [
            'count' => $notifications
        ];
    }
}
