<?php

declare(strict_types=1);

namespace App\Http\Resources\ApiGate\Merchants;

use App\Models\Merchant;
use Illuminate\Http\Resources\Json\JsonResource;

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
        /** @var Merchant|\App\Http\Resources\ApiComplianceGateway\Merchants\MerchantsResource $this */
        return [
            'id' => $this->id,
            'legal_name' => $this->name,
            'contract_number' => optional($this->whenLoaded('merchant_info'))->contract_number,
        ];
    }
}
