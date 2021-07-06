<?php

namespace App\Http\Resources\ApiPrmGateway\Merchants;

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
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
