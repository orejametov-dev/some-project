<?php

declare(strict_types=1);

namespace App\Http\Resources\ApiComplianceGateway\ProblemCases;

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
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'phone' => $this->resource->phone,
            'client_name' => $this->resource->client_name,
            'client_surname' => $this->resource->client_surname,
            'client_patronymic' => $this->resource->client_patronymic,
            'assigned_to_name' => $this->resource->assigned_to_name,
            'status_id' => $this->resource->status_id,
            'status_key' => $this->resource->status_key,
            'post_or_pre_created_by_name' => $this->resource->post_or_pre_created_by_name,
            'application_id' => $this->resource->application_id,
            'description' => $this->resource->description,
            'created_from_name' => $this->resource->created_from_name,
            'application_items' => $this->resource->application_items,
            'comment_from_merchants' => $this->resource->comment_from_merchant,
            'deadline' => $this->resource->deadline,
            'created_at' => $this->resource->created_at,
        ];
    }
}
