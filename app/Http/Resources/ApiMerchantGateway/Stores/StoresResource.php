<?php

declare(strict_types=1);

namespace App\Http\Resources\ApiMerchantGateway\Stores;

use App\Models\Store;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Store $resource
 */
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
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
        ];
    }
}
