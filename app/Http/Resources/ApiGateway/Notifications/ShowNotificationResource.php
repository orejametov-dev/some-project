<?php

declare(strict_types=1);

namespace App\Http\Resources\ApiGateway\Notifications;

use App\Http\Resources\ApiGateway\ProblemCases\IndexProblemCaseResource;
use App\Models\Notification;

/**
 * @property Notification $resource
 */
class ShowNotificationResource extends IndexProblemCaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'end_schedule' => $this->resource->end_schedule,
            'stores' => $this->whenLoaded('stores'),
        ]);
    }
}
