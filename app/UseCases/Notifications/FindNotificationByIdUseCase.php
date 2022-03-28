<?php

declare(strict_types=1);

namespace App\UseCases\Notifications;

use App\Exceptions\BusinessException;
use App\Models\Notification;

class FindNotificationByIdUseCase
{
    public function execute(int $id): Notification
    {
        $notification = Notification::query()->find($id);
        if ($notification === null) {
            throw new BusinessException('Оповещение не найдено', 'object_not_found', 404);
        }

        return  $notification;
    }
}
