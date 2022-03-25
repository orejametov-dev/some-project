<?php

namespace App\Http\Resources\ApiGateway\Complaints;

use App\Models\Complaint;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Complaint $resource
 */
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
        return [
            'meta' => $this->resource->meta,
            'created_at' => $this->resource->created_at,
        ];
    }
}
