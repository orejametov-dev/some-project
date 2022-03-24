<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiGateway\AzoMerchants\Stores;

use App\DTOs\Notifications\StoreNotificationDTO;
use App\DTOs\Notifications\UpdateNotificationDTO;
use App\Filters\CommonFilters\CreatedAtFilter;
use App\Filters\CommonFilters\CreatedByIdFilter;
use App\Filters\Notification\MerchantIdNotificationFilter;
use App\Filters\Notification\PublishedFilter;
use App\Filters\Notification\QNotificationFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiPrm\Notifications\StoreNotificationRequest;
use App\Http\Requests\ApiPrm\Notifications\UpdateNotificationRequest;
use App\Models\Notification;
use App\UseCases\Notifications\FindNotificationByIdUseCase;
use App\UseCases\Notifications\RemoveNotificationUseCase;
use App\UseCases\Notifications\StoreNotificationUseCase;
use App\UseCases\Notifications\UpdateNotificationUseCase;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function index(Request $request)
    {
        $notifications = Notification::query()
            ->filterRequest($request, [
                QNotificationFilter::class,
                CreatedAtFilter::class,
                CreatedByIdFilter::class,
                MerchantIdNotificationFilter::class,
                PublishedFilter::class,
            ])
            ->latest();

        if ($request->query('object') == true) {
            return $notifications->first();
        }

        if ($request->has('paginate') && $request->query('paginate') == false) {
            return $notifications->get();
        }

        return $notifications->paginate($request->query('per_page') ?? 15);
    }

    public function show($id, FindNotificationByIdUseCase $findNotificationByIdUseCase)
    {
        $notification = $findNotificationByIdUseCase->execute((int) $id);
        $notification->load(['stores']);

        return $notification;
    }

    public function store(StoreNotificationRequest $request, StoreNotificationUseCase $storeNotificationUseCase)
    {
        return $storeNotificationUseCase->execute(StoreNotificationDTO::fromArray($request->validated()));
    }

    public function update($id, UpdateNotificationRequest $request, UpdateNotificationUseCase $updateNotificationUseCase)
    {
        return $updateNotificationUseCase->execute((int) $id, UpdateNotificationDTO::fromArray($request->validated()));
    }

    public function remove($id, RemoveNotificationUseCase $removeNotificationUseCase)
    {
        $removeNotificationUseCase->execute((int) $id);

        return response()->json(['message' => 'Уведомление удалено успешно']);
    }
}
