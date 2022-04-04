<?php

declare(strict_types=1);

namespace App\Http\Resources\ApiGateway\Stores;

class ShowStoreResource extends StoreResource
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
            'activity_reasons' => $this->whenLoaded('activity_reasons'),
        ]);
    }
}
