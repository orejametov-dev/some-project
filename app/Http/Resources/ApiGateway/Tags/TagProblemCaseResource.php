<?php

declare(strict_types=1);

namespace App\Http\Resources\ApiGateway\Tags;

use App\Models\ProblemCaseTag;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property ProblemCaseTag $resource
 */
class TagProblemCaseResource extends JsonResource
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
            'body' => $this->resource->body,
            'type_id' => $this->resource->type_id,
            'point' => $this->resource->point,
            'created_at' => $this->resource->created_at,
        ];
    }
}
