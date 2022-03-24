<?php

declare(strict_types=1);

namespace App\UseCases\Notifications;

use App\DTOs\Notifications\UpdateNotificationDTO;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class UpdateNotificationUseCase
{
    public function __construct(
        private FindNotificationByIdUseCase $findNotificationByIdUseCase
    ) {
    }

    public function execute(int $id, UpdateNotificationDTO $updateNotificationDTO): Notification
    {
        $notification = $this->findNotificationByIdUseCase->execute($id);

        $notification->title_ru = $updateNotificationDTO->getTitleRu();
        $notification->title_uz = $updateNotificationDTO->getTitleUz();
        $notification->body_ru = $updateNotificationDTO->getBodyRu();
        $notification->body_uz = $updateNotificationDTO->getBodyUz();
        $notification->start_schedule = $updateNotificationDTO->getStartSchedule() ?? Carbon::now();
        $notification->end_schedule = $updateNotificationDTO->getEndSchedule() ?? Carbon::now()->addDay();

        $notification->save();

        Cache::tags('notifications')->flush();

        return $notification;
    }
}
