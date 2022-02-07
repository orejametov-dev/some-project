<?php

namespace App\Http\Resources\ApiPrmGateway\Merchants;

use App\Modules\Merchants\Models\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MerchantRequestsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var Request|MerchantRequestsResource $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'information' => $this->information,
            'created_at' => $this->created_at,
            'engaged_by' => [
                'engaged_by_id' => $this->engaged_by_id,
                'engaged_by_name' => $this->engaged_by_name,
            ],
            'status' => $this->status,
        ];
    }
}
