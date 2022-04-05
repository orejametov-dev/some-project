<?php

declare(strict_types=1);

namespace App\Http\Resources\ApiMerchantGateway\MerchantRequest;

use App\Models\MerchantRequest;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property MerchantRequest $resource
 */
class MerchantRequestResource extends JsonResource
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
            'user_phone' => $this->resource->user_phone,
            'user_name' => $this->resource->user_name,
            'categories' => $this->resource->categories,
            'region' => $this->resource->region,
            'district' => $this->resource->district,
            'legal_name' => $this->resource->legal_name,
            'legal_name_prefix' => $this->resource->legal_name_prefix,
            'approximate_sales' => $this->resource->approximate_sales,
            'name' => $this->resource->name,
        ];
    }
}
