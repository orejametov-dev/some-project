<?php

declare(strict_types=1);

namespace App\Http\Resources\ApiCredtisGateway\Stores;

use App\Http\Resources\ApiCredtisGateway\Merchants\MerchantsResource;
use App\Modules\Merchants\Models\Store;
use Illuminate\Http\Resources\Json\JsonResource;

class StoresResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var Store|StoresResource $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'merchant' => new MerchantsResource($this->whenLoaded('merchant')),
        ];
    }
}
