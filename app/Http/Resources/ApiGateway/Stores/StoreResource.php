<?php

declare(strict_types=1);

namespace App\Http\Resources\ApiGateway\Stores;

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
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'active' => $this->resource->active,
            'client_type_register' => $this->resource->client_type_register,
            'merchant_id' => $this->resource->merchant_id,
            'region' => $this->resource->region,
            'phone' => $this->resource->phone,
            'address' => $this->resource->address,
            'district' => $this->resource->district,
            'responsible_person' => $this->resource->responsible_person,
            'responsible_person_phone' => $this->resource->responsible_person_phone,
            'created_at' => $this->resource->created_at,
        ];
    }
}
