<?php

declare(strict_types=1);

namespace App\Http\Resources\ApiOnlineGateway;

use App\Modules\Merchants\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MerchantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var Merchant|MerchantResource $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'tags' => $this->whenLoaded('tags'),
            'logo_path' => $this->logo_path,
            'recommend' => $this->recommend,
        ];
    }
}
