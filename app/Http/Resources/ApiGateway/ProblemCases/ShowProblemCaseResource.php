<?php

declare(strict_types=1);

namespace App\Http\Resources\ApiGateway\ProblemCases;

class ShowProblemCaseResource extends ProblemCaseResource
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
            'merchant' => $this->whenLoaded('merchant'),
            'store' => $this->whenLoaded('store'),
            'tags' => $this->whenLoaded('tags'),
        ]);
    }
}
