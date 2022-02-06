<?php

namespace App\Http\Resources\ApiGate\Conditions;

use App\Modules\Merchants\Models\Condition;
use Illuminate\Http\Resources\Json\JsonResource;

class ConditionsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var Condition|ConditionsResource $this */
        return [
            'id' => $this->id,
            'merchant_id' => $this->merchant_id,
            'store_id' => $this->store_id,
            'event_id' => $this->event_id,
            'commission' => $this->commission,
            'duration' => $this->duration,
            'discount' => $this->discount,
        ];
    }
}
