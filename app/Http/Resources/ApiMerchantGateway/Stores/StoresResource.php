<?php

declare(strict_types=1);

namespace App\Http\Resources\ApiMerchantGateway\Stores;

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
        ];
    }
}
