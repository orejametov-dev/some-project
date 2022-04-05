<?php

declare(strict_types=1);

namespace App\Http\Resources\ApiOnlineGateway;

use App\Models\Condition;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Condition $resource
 */
class ConditionsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'duration' => $this->resource->duration,
            'commission' => $this->resource->commission,
        ];
    }
}
