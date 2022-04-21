<?php

namespace App\Http\Resources\ApiGateway\ApplicationConditions;

class IndexApplicationConditionResource extends ApplicationConditionResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'stores' => $this->whenLoaded('stores'),
        ]);
    }
}
