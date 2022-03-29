<?php

namespace App\Http\Resources\ApiGateway\AdditionalAgreement;

use App\Models\AdditionalAgreement;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property AdditionalAgreement $resource
 */
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
        return [
            'id' => $this->resource->id,
            'number' => $this->resource->number,
            'limit'  => $this->resource->limit,
            'limit_expired_at' => $this->resource->limit_expired_at,
            'document_type' => $this->resource->document_type,
            'created_at' => $this->resource->created_at,
        ];
    }
}
