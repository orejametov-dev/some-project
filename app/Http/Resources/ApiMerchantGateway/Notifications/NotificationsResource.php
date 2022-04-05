<?php

declare(strict_types=1);

namespace App\Http\Resources\ApiMerchantGateway\Notifications;

use App\Models\Notification;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Notification $resource
 */
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
        return [
            'id' => $this->resource->id,
            'title_ru' => $this->resource->title_ru,
            'title_uz' => $this->resource->title_uz,
            'body_uz' => $this->resource->body_uz,
            'body_ru' => $this->resource->body_ru,
            'start_schedule' => $this->resource->start_schedule,
            'end_schedule' => $this->resource->end_schedule,
            'created_at' => $this->resource->created_at,
        ];
    }
}
