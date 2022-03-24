<?php

namespace App\Http\Resources\ApiGateway\Comments;

use App\Models\Comment;
use Illuminate\Http\Resources\Json\JsonResource;

class IndexCommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        /** @var Comment|IndexCommentResource $this */
        return [
            'id' => $this->id,
            'created_by_name' => $this->created_by_name,
            'body' => $this->body,
            'created_at' => $this->created_at,
        ];
    }
}
