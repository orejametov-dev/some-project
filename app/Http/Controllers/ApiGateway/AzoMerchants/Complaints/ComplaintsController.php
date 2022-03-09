<?php

namespace App\Http\Controllers\ApiGateway\AzoMerchants\Complaints;

use App\Filters\CommonFilters\IdFilter;
use App\Filters\Complaint\AzoMerchantAccessByAccessIdFilter;
use App\Filters\Complaint\AzoMerchantAccessIdByUserIdFilter;
use App\Http\Controllers\Controller;
use App\Modules\Merchants\Models\Complaint;
use Illuminate\Http\Request;

class ComplaintsController extends Controller
{
    public function index(Request $request)
    {
        $complaints = Complaint::query()
            ->with('azo_merchant_access')
            ->filterRequest($request, [
                IdFilter::class,
                AzoMerchantAccessIdByUserIdFilter::class,
                AzoMerchantAccessByAccessIdFilter::class,
            ])
            ->orderRequest($request);

        return $complaints->paginate($request->input('per_page') ?? 15);
    }
}
