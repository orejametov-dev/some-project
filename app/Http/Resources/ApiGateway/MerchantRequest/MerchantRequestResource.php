<?php

declare(strict_types=1);

namespace App\Http\Resources\ApiGateway\MerchantRequest;

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
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'status_id' => $this->resource->status_id,
            'cancel_reason_id' => $this->resource->cancel_reason_id,
            'user_phone' => $this->resource->user_phone,
            'user_name' => $this->resource->user_name,
            'legal_name' => $this->resource->legal_name,
            'legal_name_prefix' => $this->resource->legal_name_prefix,
            'categories' => $this->resource->categories,
            'stores_count' => $this->resource->stores_count,
            'merchant_users_count' => $this->resource->merchant_users_count,
            'region' => $this->resource->region,
            'district' => $this->resource->district,
            'approximate_sales' => $this->resource->approximate_sales,
            'director_name' => $this->resource->director_name,
            'vat_number' => $this->resource->vat_number,
            'tin' => $this->resource->tin,
            'oked' => $this->resource->oked,
            'phone' => $this->resource->phone,
            'mfo' => $this->resource->mfo,
            'bank_account' => $this->resource->bank_account,
            'bank_name' => $this->resource->bank_name,
            'address' => $this->resource->address,
            'engaged_by_id' => $this->resource->engaged_by_id,

        ];
    }
}
