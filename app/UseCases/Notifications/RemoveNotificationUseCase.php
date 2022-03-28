<?php

declare(strict_types=1);

namespace App\UseCases\Notifications;

use Illuminate\Support\Facades\Cache;

class RemoveNotificationUseCase
{
    public function __construct(
        private FindNotificationByIdUseCase $findNotificationByIdUseCase
    ) {
    }

    public function execute(int $id): void
    {
        $notification = $this->findNotificationByIdUseCase->execute($id);

        $notification->stores()->detach();
        $notification->delete();

        Cache::tags('notifications')->flush();
    }
}
