<?php

declare(strict_types=1);

namespace App\Http\Resources\ApiCallsGateway\Stores;

use App\Models\Store;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Store $resource
 */
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
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'merchant_id' => $this->resource->merchant_id,
            'address' => $this->resource->address,
            'phone' => $this->resource->phone,
            'responsible_person' => $this->resource->responsible_person,
        ];
    }
}
