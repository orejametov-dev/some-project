<?php

namespace App\Http\Resources\ApiMerchantGateway\ProblemCases;

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
            'merchant_id' => $this->merchant_id,
            'store_id' => $this->merchant_id,
            'credit_number' => $this->credit_number ?? null,
            'application_id' => $this->application_id ?? null,
            'client_id' => $this->client_id,
            'title' => $this->search_index,
            'status_key' => $this->status_key,
            'status_id' => $this->status_id,
            'created_by_id' => $this->created_by_id ?? null,
            'created_by_name' => $this->created_by_name ?? null,
            'merchant_comment' => $this->merchant_comment ?? null,
            'application_items' => $this->application_items,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'tags' => $this->before_tags
        ];
    }
}
