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
use App\Http\Resources\ApiGateway\Notifications\ShowNotificationResource;
use App\Http\Resources\ApiMerchantGateway\Notifications\NotificationsResource;
use App\Models\Notification;
use App\UseCases\Notifications\FindNotificationByIdUseCase;
use App\UseCases\Notifications\RemoveNotificationUseCase;
use App\UseCases\Notifications\StoreNotificationUseCase;
use App\UseCases\Notifications\UpdateNotificationUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationsController extends Controller
{
    public function index(Request $request): JsonResource
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
            return new NotificationsResource($notifications->first());
        }

        if ($request->has('paginate') && $request->query('paginate') == false) {
            return NotificationsResource::collection($notifications->get());
        }

        return NotificationsResource::collection($notifications->paginate($request->query('per_page') ?? 15));
    }

    public function show(int $id, FindNotificationByIdUseCase $findNotificationByIdUseCase): ShowNotificationResource
    {
        $notification = $findNotificationByIdUseCase->execute($id);
        $notification->load(['stores']);

        return new ShowNotificationResource($notification);
    }

    public function store(StoreNotificationRequest $request, StoreNotificationUseCase $storeNotificationUseCase): NotificationsResource
    {
        $notification = $storeNotificationUseCase->execute(StoreNotificationDTO::fromArray($request->validated()));

        return new NotificationsResource($notification);
    }

    public function update(int $id, UpdateNotificationRequest $request, UpdateNotificationUseCase $updateNotificationUseCase): NotificationsResource
    {
        $notification = $updateNotificationUseCase->execute($id, UpdateNotificationDTO::fromArray($request->validated()));

        return new NotificationsResource($notification);
    }

    public function remove(int $id, RemoveNotificationUseCase $removeNotificationUseCase): JsonResponse
    {
        $removeNotificationUseCase->execute($id);

        return new JsonResponse(['message' => 'Уведомление удалено успешно']);
    }
}
