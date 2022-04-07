<?php

declare(strict_types=1);

namespace App\Http\Resources\ApiGate\Conditions;

use App\Models\Condition;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Condition $resource
 */
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
        return [
            'id' => $this->resource->id,
            'merchant_id' => $this->resource->merchant_id,
            'store_id' => $this->resource->store_id,
            'event_id' => $this->resource->event_id,
            'commission' => $this->resource->commission,
            'duration' => $this->resource->duration,
            'discount' => $this->resource->discount,
        ];
    }
}
