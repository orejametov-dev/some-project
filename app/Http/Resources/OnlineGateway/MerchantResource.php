<?php

namespace App\Http\Resources\OnlineGateway;

use App\Modules\Merchants\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MerchantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var Merchant|MerchantResource $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'information' => $this->information,
            'logo_path' => $this->logo_path
        ];
    }
}
