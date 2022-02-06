<?php

namespace App\Http\Resources\ApiPrmGateway\Merchants;

use App\Modules\Merchants\Models\Merchant;
use Illuminate\Http\Resources\Json\JsonResource;
use function Symfony\Component\Translation\t;

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
        /** @var Merchant|MerchantsResource $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
