<?php

namespace App\Http\Resources\OnlineGateway;

use App\Modules\Partners\Models\MerchantTag;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MerchantTagResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var MerchantTag|MerchantTagResource $this */
        return [
            'id' => $this->id,
            'name' => $this->title,
        ];
    }
}
