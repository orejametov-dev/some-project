<?php

declare(strict_types=1);

namespace App\Http\Resources\ApiGateway\MerchantRequest;

use App\Models\MerchantRequest;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property MerchantRequest $resource
 */
class IndexMerchantRequestResource extends JsonResource
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
            'created_from_name' => $this->resource->created_from_name,
            'created_at' => $this->resource->created_at,
            'engaged_by' => [
                'engaged_by_id' => $this->resource->engaged_by_id,
                'engaged_by_name' => $this->resource->engaged_by_name,
            ],
            'status' => $this->resource->status,
        ];
    }
}
