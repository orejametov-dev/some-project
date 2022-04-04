<?php

declare(strict_types=1);

namespace App\Http\Resources\ApiGateway\Files;

use App\Models\File;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property File $resource
 */
class FileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'file_type' => $this->resource->file_type,
            'mime_type' => $this->resource->mime_type,
            'url' => $this->resource->url,
            'size' => $this->resource->size,
            'merchant_id' => $this->resource->merchant_id,
            'link' => $this->resource->getLinkAttribute(),
        ];
    }
}
