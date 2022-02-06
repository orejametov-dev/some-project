<?php

namespace App\Http\Resources\ApiCredtisGateway\Merchants;

use App\Modules\Merchants\Models\Merchant;
use App\Services\LegalNameService;
use Illuminate\Http\Resources\Json\JsonResource;

class SpecialMerchantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /* @var Merchant|SpecialMerchantResource $this */
        return [
            'merchant_ids' => $this->merchant_ids, /* @phpstan-ignore-line */
            'legal_name' => LegalNameService::findNamePrefix($this->legal_name_prefix)['body_ru']['value'] . ' ' . $this->legal_name,
            'logo_path' => $this->logo_path,
        ];
    }
}
