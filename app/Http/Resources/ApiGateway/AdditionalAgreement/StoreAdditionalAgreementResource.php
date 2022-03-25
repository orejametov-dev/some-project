<?php

namespace App\Http\Resources\ApiGateway\AdditionalAgreement;

use App\Models\AdditionalAgreement;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property AdditionalAgreement $resource
 */
class StoreAdditionalAgreementResource extends JsonResource
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
            'merchant_id' => $this->resource->merchant_id,
            'number' => $this->resource->number,
            'document_type' => $this->resource->document_type,
            'registration_date' => $this->resource->registration_date,
            'limit' => $this->resource->limit,
        ];
    }
}
