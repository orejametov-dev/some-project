<?php

namespace App\Http\Resources\ApiGateway\Complaints;

use App\Models\Complaint;
use Illuminate\Http\Resources\Json\JsonResource;

class IndexComplaintResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        /** @var Complaint|IndexComplaintResource $this */
        return [
            'meta' => $this->meta,
            'created_at' => $this->created_at,
        ];
    }
}
