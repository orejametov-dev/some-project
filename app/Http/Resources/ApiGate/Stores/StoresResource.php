<?php

namespace App\Http\Resources\ApiGate\Stores;

use Illuminate\Http\Resources\Json\JsonResource;

class StoresResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'merchant_id' => $this->merchant_id,
            'phone' => $this->phone,
            'region' => $this->region
        ];
    }
}
