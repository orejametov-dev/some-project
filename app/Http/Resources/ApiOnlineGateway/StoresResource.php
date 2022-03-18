<?php

declare(strict_types=1);

namespace App\Http\Resources\ApiOnlineGateway;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoresResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var Store|StoresResource $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'address_phone' => $this->address . ' ' . $this->phone,
            'address' => $this->address,
            'phone' => $this->phone,
        ];
    }
}
