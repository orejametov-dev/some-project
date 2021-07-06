<?php

namespace App\Http\Resources\ApiMerchantGateway\Notifications;

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
            'id' => $this->id,
            'title_ru' => $this->title_ru,
            'title_uz' => $this->title_uz,
            'body_uz' => $this->body_uz,
            'body_ru' => $this->body_ru,
            'start_schedule' => $this->start_schedule,
            'end_schedule' => $this->end_schedule,
            'created_at' => $this->created_at
        ];
    }
}
