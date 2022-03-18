<?php

declare(strict_types=1);

namespace App\Http\Resources\ApiOnlineGateway;

use App\Models\Condition;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
        /** @var Condition|ConditionsResource $this */
        return [
            'id' => $this->id,
            'duration' => $this->duration,
            'commission' => $this->commission,
        ];
    }
}
