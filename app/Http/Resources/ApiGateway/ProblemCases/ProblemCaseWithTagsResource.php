<?php

declare(strict_types=1);

namespace App\Http\Resources\ApiGateway\ProblemCases;

class ProblemCaseWithTagsResource extends ProblemCaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'tags' => $this->whenLoaded('tags'),
        ]);
    }
}
