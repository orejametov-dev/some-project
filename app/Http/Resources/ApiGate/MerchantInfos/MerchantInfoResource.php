<?php

namespace App\Http\Resources\ApiGate\MerchantInfos;

use App\Modules\Merchants\Models\MerchantInfo;
use Illuminate\Http\Resources\Json\JsonResource;

class MerchantInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        /** @var MerchantInfo|MerchantInfoResource $this */
        return [
            'merchant_id' => $this->merchant_id,
            'contract_number' => $this->contract_number,
            'contract_date' => $this->contract_date,
            'tin' => $this->tin,
        ];
    }
}
