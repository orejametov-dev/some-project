<?php

declare(strict_types=1);

namespace App\Http\Resources\ApiCallsGateway\Stores;

use App\Models\Store;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var Store|StoreResource $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'merchant_id' => $this->merchant_id,
            'address' => $this->address,
            'phone' => $this->phone,
            'responsible_person' => $this->responsible_person,
        ];
    }
}
