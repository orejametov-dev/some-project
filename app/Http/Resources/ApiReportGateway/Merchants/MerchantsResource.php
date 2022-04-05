<?php

declare(strict_types=1);

namespace App\Http\Resources\ApiReportGateway\Merchants;

use App\Models\Merchant;
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
            'legal_name' => $this->resource->legal_name,
            'legal_name_prefix' => $this->resource->legal_name_prefix,
        ];
    }
}
