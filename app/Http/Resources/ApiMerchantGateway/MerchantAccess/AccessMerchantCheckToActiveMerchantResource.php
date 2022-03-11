<?php

namespace App\Http\Resources\ApiMerchantGateway\MerchantAccess;

use App\Modules\Merchants\Models\AzoMerchantAccess;
use Illuminate\Http\Resources\Json\JsonResource;

class AccessMerchantCheckToActiveMerchantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        /** @var AzoMerchantAccess|AccessMerchantCheckToActiveMerchantResource $this */
        return [
            'merchant_name' => $this->whenLoaded('merchant')->name,
            'merchant_active' => $this->whenLoaded('merchant')->active,
            'store' => $this->whenLoaded('store')->name,
            'store_active' => $this->whenLoaded('store')->active,
        ];
    }
}
