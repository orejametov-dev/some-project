<?php

namespace App\Http\Resources\ApiGateway\AdditionalAgreement;

use App\Models\AdditionalAgreement;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property AdditionalAgreement $resource
 */
class UpdateAdditionalAgreementResource extends JsonResource
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
            'limit' => $this->resource->limit,
            'registration_date' => $this->resource->registration_date,
            'number' => $this->resource->number,
            'merchant_id' => $this->resource->merchant_id,
            'document_type' => $this->resource->document_type,
        ];
    }
}
