<?php

declare(strict_types=1);

namespace App\Http\Resources\ApiComplianceGateway\Stores;

use App\Http\Resources\ApiMerchantGateway\ProblemCases\ProblemCaseResource;
use App\Modules\Merchants\Models\ProblemCase;
use Illuminate\Http\Resources\Json\JsonResource;

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
        /** @var ProblemCase|ProblemCaseResource $this */
        return parent::toArray($request);
//        return [
//            'id' => $this->id,
//            'name' => $this->name,
//            'merchant_id' => $this->merchant_id,
//            'created_at' => $this->created_at,
//            'updated_at' => $this->updated_at
//        ];
    }
}
