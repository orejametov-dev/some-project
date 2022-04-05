<?php

declare(strict_types=1);

namespace App\Http\Resources\ApiMerchantGateway\ProblemCases;

use App\Http\Resources\ApiMerchantGateway\Stores\StoresResource;
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
            'merchant_id' => $this->resource->merchant_id,
            'store_id' => $this->resource->store_id,
            'store' => new StoresResource($this->resource->store),
            'credit_number' => $this->resource->credit_number ?? null,
            'application_id' => $this->resource->application_id ?? null,
            'client_id' => $this->resource->client_id,
            'title' => $this->resource->search_index,
            'description' => $this->resource->description,
            'status_key' => $this->resource->status_key,
            'status_id' => $this->resource->status_id,
            'created_by_id' => $this->resource->created_by_id ?? null,
            'created_by_name' => $this->resource->created_by_name ?? null,
            'merchant_comment' => $this->resource->merchant_comment ?? null,
            'application_items' => $this->resource->application_items,
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
            'tags' => $this->resource->before_tags,
            'engaged_by_id' => $this->resource->engaged_by_id,
            'engaged_by_name' => $this->resource->engaged_by_name,
            'deadline' => $this->resource->deadline,
            'comment_from_merchant' => $this->resource->comment_from_merchant,
        ];
    }
}
