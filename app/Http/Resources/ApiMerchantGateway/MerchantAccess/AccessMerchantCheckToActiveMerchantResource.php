<?php

namespace App\Http\Resources\ApiMerchantGateway\MerchantAccess;

use App\Modules\Merchants\Models\Merchant;
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
        /** @var Merchant|AccessMerchantCheckToActiveMerchantResource $this */
        return [
            'name' => $this->name,
            'active' => $this->active,
        ];
    }
}
