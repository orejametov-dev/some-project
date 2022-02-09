<?php

namespace App\Http\Resources\ApiCallsGateway\ProblemCases;

use App\Http\Resources\ApiCallsGateway\Merchants\MerchantResource;
use App\Modules\Merchants\Models\ProblemCase;
use Illuminate\Http\Resources\Json\JsonResource;

class ProblemCaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var ProblemCase|ProblemCaseResource $this */
        return [
            'id' => $this->id,
            'credit_number' => $this->credit_number ?? null,
            'application_id' => $this->application_id ?? null,
            'client_id' => $this->client_id,
            'title' => $this->search_index,
            'description' => $this->description,
            'merchant' => new MerchantResource($this->whenLoaded('merchant')),
            'tags' => $this->whenLoaded('tags'),
            'merchant_comment' => $this->merchant_comment ?? null,
            'status_id' => $this->status_id,
            'assigned_to_id' => $this->assigned_to_id,
            'assigned_to_name' => $this->assigned_to_name,
            'created_at' => $this->created_at,
        ];
    }
}
