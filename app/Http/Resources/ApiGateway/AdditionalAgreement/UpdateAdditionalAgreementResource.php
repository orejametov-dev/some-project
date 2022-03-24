<?php

namespace App\Http\Resources\ApiGateway\AdditionalAgreement;

use App\Models\AdditionalAgreement;
use Illuminate\Http\Resources\Json\JsonResource;

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
        /** @var AdditionalAgreement|UpdateAdditionalAgreementResource $this */
        return [
            'limit' => $this->limit,
            'registration_date' => $this->registration_date,
            'number' => $this->number,
            'merchant_id' => $this->merchant_id,
            'document_type' => $this->document_type,
        ];
    }
}
