<?php

namespace App\Http\Controllers\ApiGateway\AzoMerchants\Complaints;

use App\Http\Controllers\Controller;
use App\Modules\Merchants\Models\Complaint;
use Illuminate\Http\Request;

class ComplaintsController extends Controller
{
    public function index(Request $request)
    {
        $complaints = Complaint::query()
            ->with('azo_merchant_access')
            ->filterRequest($request)
            ->orderRequest($request);

        return $complaints->paginate($request->input('per_page') ?? 15);
    }
}
