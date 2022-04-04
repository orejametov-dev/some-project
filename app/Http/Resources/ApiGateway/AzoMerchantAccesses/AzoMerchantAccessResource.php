<?php

declare(strict_types=1);

namespace App\Http\Resources\ApiGateway\AzoMerchantAccesses;

use App\Models\AzoMerchantAccess;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property AzoMerchantAccess $resource
 */
class AzoMerchantAccessResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'user_id' => $this->resource->user_id,
            'user_name' => $this->resource->user_name,
            'phone' => $this->resource->phone,
            'store_id' => $this->resource->store_id,
            'created_at' => $this->resource->created_at,
            'store' => $this->whenLoaded('store'),
            'merchant' => $this->whenLoaded('merchant'),
        ];
    }
}
