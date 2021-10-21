<?php

namespace App\Http\Resources\ApiCredtisGateway\Merchants;

use Illuminate\Http\Resources\Json\JsonResource;

class MerchantsResource extends JsonResource
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
            'name' => $this->name,
            'legal_name' => $this->legal_name,
            'tin' => optional($this->whenLoaded('merchant_info'))->tin
        ];
    }
}
