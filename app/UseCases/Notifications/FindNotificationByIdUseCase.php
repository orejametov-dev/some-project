<?php

declare(strict_types=1);

namespace App\UseCases\Notifications;

use App\Exceptions\NotFoundException;
use App\Models\Notification;

class FindNotificationByIdUseCase
{
    public function execute(int $id): Notification
    {
        $notification = Notification::query()->find($id);
        if ($notification === null) {
            throw new NotFoundException('Оповещение не найдено');
        }

        return  $notification;
    }
}
