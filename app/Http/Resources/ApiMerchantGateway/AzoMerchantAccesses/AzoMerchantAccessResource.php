<?php

declare(strict_types=1);

namespace App\Http\Resources\ApiMerchantGateway\AzoMerchantAccesses;

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
            'store_id' => $this->resource->store_id,
            'user_name' => $this->resource->user_name,
            'phone' => $this->resource->phone,
            'created_at' => $this->resource->created_at,
        ];
    }
}
