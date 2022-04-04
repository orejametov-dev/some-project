<?php

declare(strict_types=1);

namespace App\Http\Resources\ApiGateway\Stores;

use App\Models\Store;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Store $resource
 */
class IndexStoreResource extends JsonResource
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
            'region' => $this->resource->region,
            'created_at' => $this->resource->created_at,
            'merchant' => $this->whenLoaded('merchant'),
        ];
    }
}
