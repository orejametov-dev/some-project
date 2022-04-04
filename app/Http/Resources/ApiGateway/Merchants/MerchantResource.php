<?php

declare(strict_types=1);

namespace App\Http\Resources\ApiGateway\Merchants;

use App\Models\Merchant;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Merchant $resource
 */
class MerchantResource extends JsonResource
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
            'name' => $this->resource->name,
            'legal_name' => $this->resource->legal_name,
            'legal_name_prefix' => $this->resource->legal_name_prefix,
            'active' => $this->resource->active,
            'maintainer_id' => $this->resource->maintainer_id,
            'created_at' => $this->resource->created_at,
            ];
    }
}
