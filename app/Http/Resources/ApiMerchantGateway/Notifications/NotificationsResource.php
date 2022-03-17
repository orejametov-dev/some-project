<?php

declare(strict_types=1);

namespace App\Http\Resources\ApiMerchantGateway\Notifications;

use App\Models\Notification;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var Notification|NotificationsResource $this */
        return [
            'id' => $this->id,
            'title_ru' => $this->title_ru,
            'title_uz' => $this->title_uz,
            'body_uz' => $this->body_uz,
            'body_ru' => $this->body_ru,
            'start_schedule' => $this->start_schedule,
            'end_schedule' => $this->end_schedule,
            'created_at' => $this->created_at,
        ];
    }
}
