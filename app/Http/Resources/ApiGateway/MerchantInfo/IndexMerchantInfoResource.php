<?php

declare(strict_types=1);

namespace App\Http\Resources\ApiGateway\MerchantInfo;

class IndexMerchantInfoResource extends MerchantInfoResource
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
        ]);
    }
}
