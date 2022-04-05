<?php

declare(strict_types=1);

namespace App\Http\Resources\ApiComplianceGateway\Stores;

use App\Models\ProblemCase;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property ProblemCase $resource
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
        return parent::toArray($request);
    }
}
