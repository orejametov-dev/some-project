<?php

declare(strict_types=1);

namespace App\Http\Resources\ApiGate\Merchants;

use App\Http\Resources\ApiGate\Conditions\ConditionsResource;
use App\Http\Resources\ApiGate\Stores\StoresResource;
use App\Models\Merchant;
use App\Models\Store;
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
            'token' => $this->resource->token,
            'company_id' => $this->resource->company_id,
            'main_store' => new StoresResource(Store::query()->where('merchant_id', $this->resource->id)->where('is_main', true)->first() ?? null),
            'conditions' => ConditionsResource::collection($this->whenLoaded('application_active_conditions')),
        ];
    }
}
