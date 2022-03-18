<?php

declare(strict_types=1);

namespace App\Http\Resources\ApiCallsGateway\Merchants;

use App\Models\Merchant;
use Illuminate\Http\Resources\Json\JsonResource;

class MerchantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var Merchant|MerchantResource $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
