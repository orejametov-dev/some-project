<?php

declare(strict_types=1);

namespace App\Http\Resources\ApiGateway\Merchants;

use App\Models\Merchant;

/**
 * @property Merchant $resource
 */
class ShowMerchantResource extends MerchantResource
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
            'token' => $this->resource->token,
            'has_generate_goods' => $this->resource->has_general_goods,
            'holding_initial_payment' => $this->resource->holding_initial_payment,
            'integration' => $this->resource->integration,
            'logo_path' => $this->resource->logo_path,
            'recommend' =>$this->resource->recommend,
            'stores' => $this->whenLoaded('stores'),
            'tags' => $this->whenLoaded('tags'),
            'activity_reasons' => $this->whenLoaded('activity_reasons'),
            'competitors' => $this->whenLoaded('competitors'),
        ]);
    }
}
