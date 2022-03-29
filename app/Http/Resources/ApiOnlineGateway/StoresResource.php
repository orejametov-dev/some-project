<?php

declare(strict_types=1);

namespace App\Http\Resources\ApiOnlineGateway;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Store $resource
 */
class StoresResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array|null
     */
    public function toArray($request)
    {
        if ($this->resource === null) {
            return null;
        }

        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'address_phone' => $this->resource->address . ' ' . $this->resource->phone,
            'address' => $this->resource->address,
            'phone' => $this->resource->phone,
        ];
    }
}
