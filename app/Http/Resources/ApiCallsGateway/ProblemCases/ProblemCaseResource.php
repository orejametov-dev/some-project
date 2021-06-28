<?php

namespace App\Http\Resources\ApiCallsGateway\ProblemCases;

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
            'merchant_comment' => $this->merchant_comment ?? null,
            'created_at' => $this->created_at,
        ];
    }
}
