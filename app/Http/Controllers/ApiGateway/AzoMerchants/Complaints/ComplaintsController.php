<?php

namespace App\Http\Controllers\ApiGateway\AzoMerchants\Complaints;

use App\Filters\CommonFilters\IdFilter;
use App\Filters\Complaint\AzoMerchantAccessByAccessIdFilter;
use App\Filters\Complaint\AzoMerchantAccessIdByUserIdFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiGateway\Complaints\IndexComplaintResource;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ComplaintsController extends Controller
{
    public function index(Request $request): JsonResource
    {
        $complaints = Complaint::query()
            ->with('azo_merchant_access')
            ->filterRequest($request, [
                IdFilter::class,
                AzoMerchantAccessIdByUserIdFilter::class,
                AzoMerchantAccessByAccessIdFilter::class,
            ])
            ->orderRequest($request);

        return IndexComplaintResource::collection($complaints->paginate($request->input('per_page') ?? 15));
    }
}
