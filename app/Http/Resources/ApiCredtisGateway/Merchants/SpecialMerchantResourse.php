<?php

namespace App\Http\Resources\ApiCredtisGateway\Merchants;

use App\Services\LegalNameService;
use Illuminate\Http\Resources\Json\JsonResource;

class SpecialMerchantResourse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'merchant_ids' => $this->merchant_ids,
            'legal_name' => LegalNameService::findNamePrefix($this->legal_name_prefix)['body_ru']['value'] . ' ' . $this->legal_name,
            'logo_path' => $this->logo_path
        ];
    }
}
