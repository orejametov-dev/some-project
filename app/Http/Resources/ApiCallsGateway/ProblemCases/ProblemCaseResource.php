<?php

declare(strict_types=1);

namespace App\Http\Resources\ApiCallsGateway\ProblemCases;

use App\Http\Resources\ApiCallsGateway\Merchants\MerchantResource;
use App\Models\ProblemCase;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property ProblemCase $resource
 */
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
        return [
            'id' => $this->resource->id,
            'credit_number' => $this->resource->credit_number ?? null,
            'application_id' => $this->resource->application_id ?? null,
            'client_id' => $this->resource->client_id,
            'title' => $this->resource->search_index,
            'description' => $this->resource->description,
            'merchant' => new MerchantResource($this->whenLoaded('merchant')),
            'tags' => $this->whenLoaded('before_tags'),
            'merchant_comment' => $this->resource->merchant_comment ?? null,
            'status_id' => $this->resource->status_id,
            'assigned_to_id' => $this->resource->assigned_to_id,
            'assigned_to_name' => $this->resource->assigned_to_name,
            'created_at' => $this->resource->created_at,
        ];
    }
}
