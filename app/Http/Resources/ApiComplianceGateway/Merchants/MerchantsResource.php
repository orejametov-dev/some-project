<?php

declare(strict_types=1);

namespace App\Http\Resources\ApiComplianceGateway\Merchants;

use App\Models\Merchant;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Merchant $resource
 */
class MerchantsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
