<?php

namespace App\Http\Resources\ApiGate\MerchantInfos;

use App\Models\MerchantInfo;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property MerchantInfo $resource
 */
class MerchantInfoResource extends JsonResource
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
            'merchant_id' => $this->resource->merchant_id,
            'director_name' =>  $this->resource->director_name,
            'contract_number' => $this->resource->contract_number,
            'contract_date' => $this->resource->contract_date,
            'tin' => $this->resource->tin,
        ];
    }
}
