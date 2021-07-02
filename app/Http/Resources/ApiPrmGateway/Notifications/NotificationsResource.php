<?php

namespace App\Http\Resources\ApiPrmGateway\Notifications;

use App\Http\Resources\ApiPrmGateway\Merchants\MerchantsResource;
use App\Http\Resources\ApiPrmGateway\Stores\StoresResource;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'title' => $this->title_ru,
            'start_schedule' => $this->start_schedule,
            'end_schedule' => $this->end_schedule,
            'stores' => StoresResource::collection($this->stores),
            'merchants' => MerchantsResource::collection($this->merchants)
        ];
    }
}
