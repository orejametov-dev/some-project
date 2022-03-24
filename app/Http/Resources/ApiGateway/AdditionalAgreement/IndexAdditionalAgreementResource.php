<?php

namespace App\Http\Resources\ApiGateway\AdditionalAgreement;

use App\Models\AdditionalAgreement;
use Illuminate\Http\Resources\Json\JsonResource;

class IndexAdditionalAgreementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        /** @var AdditionalAgreement|IndexAdditionalAgreementResource $this */
        return [
            'id' => $this->id,
            'number' => $this->number,
            'limit'  => $this->limit,
            'limit_expired_at' => $this->limit_expired_at,
            'document_type' => $this->document_type,
            'created_at' => $this->created_at,
        ];
    }
}
