<?php

declare(strict_types=1);

namespace App\Http\Resources\ApiComplianceGateway\Merchants;

use App\Models\Merchant;
use Illuminate\Http\Resources\Json\JsonResource;

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
        /** @var Merchant|MerchantsResource $this */
        return parent::toArray($request);
//        return [
//            'id' => $this->id,
//            'name' => $this->name,
//            'logo_path' => $this->logo_path,
//            'create_at' => $this->created_at,
//            'updated_at' => $this->updated_at
//        ];
    }
}
