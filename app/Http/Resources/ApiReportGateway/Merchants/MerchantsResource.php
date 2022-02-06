<?php

namespace App\Http\Resources\ApiReportGateway\Merchants;

use App\Modules\Merchants\Models\Merchant;
use Illuminate\Http\Resources\Json\JsonResource;

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
        /* @var Merchant|MerchantsResource $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'legal_name' => $this->legal_name,
            'legal_name_prefix' => $this->legal_name_prefix,
        ];
    }
}
