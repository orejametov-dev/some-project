<?php

declare(strict_types=1);

namespace App\Http\Resources\ApiCredtisGateway\Merchants;

use App\Models\Merchant;
use App\Services\LegalNameService;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Merchant $resource
 */
class MerchantsResource extends JsonResource
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
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'legal_name' => LegalNameService::findNamePrefix($this->resource->legal_name_prefix)['body_ru']['value'] . ' ' . $this->resource->legal_name,
            'tin' => optional($this->whenLoaded('merchant_info'))->tin,
        ];
    }
}
