<?php

declare(strict_types=1);

namespace App\Http\Resources\ApiCallsGateway\Comments;

use App\Models\Comment;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Comment $resource
 */
class CommentResource extends JsonResource
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
            'created_by_name' => $this->resource->created_by_name,
            'body' => $this->resource->body,
            'created_at' => $this->resource->created_at,
        ];
    }
}
