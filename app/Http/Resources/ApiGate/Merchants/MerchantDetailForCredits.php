<?php

declare(strict_types=1);

namespace App\Http\Resources\ApiGate\Merchants;

use App\Models\Merchant;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Merchant $resource
 */
class MerchantDetailForCredits extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'legal_name' => $this->resource->name,
            'contract_number' => optional($this->whenLoaded('merchant_info'))->contract_number,
        ];
    }
}
