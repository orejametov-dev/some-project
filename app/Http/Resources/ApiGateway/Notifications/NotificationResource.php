<?php

declare(strict_types=1);

namespace App\Http\Resources\ApiGateway\Notifications;

use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Notification $resource
 */
class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'title_ru' => $this->resource->title_ru,
            'title_uz' => $this->resource->title_uz,
            'created_by_name' => $this->resource->created_by_name,
            'start_schedule' => Carbon::parse($this->resource->start_schedule),
        ];
    }
}
