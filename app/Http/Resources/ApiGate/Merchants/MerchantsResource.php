<?php

namespace App\Http\Resources\ApiGate\Merchants;

use App\Http\Resources\ApiGate\Conditions\ConditionsResource;
use App\Http\Resources\ApiGate\Stores\StoresResource;
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
        /** @var Merchant|\App\Http\Resources\ApiComplianceGateway\Merchants\MerchantsResource $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'token' => $this->token,
            'main_store' => new StoresResource($this->main_store),
            'conditions' => ConditionsResource::collection($this->whenLoaded('application_active_conditions'))
        ];
    }
}
