<?php

declare(strict_types=1);

namespace App\Http\Resources\ApiGateway\ApplicationConditions;

use App\Http\Resources\ApiGateway\Stores\StoreResource;
use App\Models\Condition;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Condition $resource
 */
class ApplicationConditionResource extends JsonResource
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
            'is_special' => $this->resource->is_special,
            'special_offer' => $this->resource->special_offer,
            'duration' => $this->resource->duration,
            'commission' => $this->resource->commission,
            'discount' => $this->resource->discount,
            'active' => $this->resource->active,
            'updated_at' => $this->resource->updated_at,
            'post_merchant' => $this->resource->post_merchant,
            'post_alifshop' => $this->resource->post_alifshop,
            'stores' => $this->whenLoaded('stores')
        ];
    }
}
