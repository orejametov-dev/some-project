<?php

declare(strict_types=1);

namespace App\Http\Resources\ApiGateway\MerchantInfo;

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
            'id' => $this->resource->id,
            'merchant_id' => $this->resource->id,
            'limit' => $this->resource->limit,
            'reset_limit' => $this->resource->rest_limit,
            'contract_date' => $this->resource->contract_date,
            'contract_number' => $this->resource->contract_number,
            'limit_expired_at' => $this->resource->limit_expired_at,
            'director_name' => $this->resource->director_name,
            'phone' => $this->resource->phone,
            'vat_number' => $this->resource->vat_number,
            'mfo' => $this->resource->mfo,
            'tin' => $this->resource->tin,
            'oked' => $this->resource->oked,
            'address' => $this->resource->address,
            'bank_account' => $this->resource->bank_account,
            'bank_name' => $this->resource->bank_name,
            ];
    }
}
