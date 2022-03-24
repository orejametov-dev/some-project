<?php

namespace App\Http\Resources\ApiGateway\AdditionalAgreement;

use App\Models\AdditionalAgreement;
use Illuminate\Http\Resources\Json\JsonResource;

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
        /** @var AdditionalAgreement|StoreAdditionalAgreementResource $this */
        return [
            'merchant_id' => $this->merchant_id,
            'number' => $this->number,
            'document_type' => $this->document_type,
            'registration_date' => $this->registration_date,
            'limit' => $this->limit,
        ];
    }
}
