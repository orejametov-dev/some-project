<?php

declare(strict_types=1);

namespace App\Http\Resources\ApiCredtisGateway\Merchants;

use App\Models\Merchant;
use App\Services\LegalNameService;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Merchant $resource
 */
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
        return [
            'merchant_ids' => $this->resource->merchant_ids, /* @phpstan-ignore-line */
            'legal_name' => LegalNameService::findNamePrefix($this->resource->legal_name_prefix)['body_ru']['value'] . ' ' . $this->resource->legal_name,
            'logo_path' => $this->resource->logo_path,
        ];
    }
}
