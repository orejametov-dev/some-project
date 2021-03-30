<?php

namespace App\Http\Resources\OnlineGateway;

use App\Modules\Merchants\Models\Tag;
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
        /** @var Tag|MerchantTagResource $this */
        return [
            'id' => $this->id,
            'name' => $this->title,
        ];
    }
}
