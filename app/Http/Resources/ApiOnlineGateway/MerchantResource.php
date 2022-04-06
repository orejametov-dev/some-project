<?php

declare(strict_types=1);

namespace App\Http\Resources\ApiOnlineGateway;

use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Merchant $resource
 */
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
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'tags' => $this->whenLoaded('tags'),
            'logo_path' => $this->resource->logo_path,
            'integration' => $this->resource->integration,
            'recommend' => $this->resource->recommend,
        ];
    }
}
