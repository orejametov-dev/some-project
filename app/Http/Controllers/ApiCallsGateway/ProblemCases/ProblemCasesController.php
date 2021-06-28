<?php


namespace App\Http\Controllers\ApiCallsGateway\ProblemCases;


use App\Http\Controllers\ApiMerchantGateway\ApiBaseController;
use App\Http\Resources\ApiCallsGateway\ProblemCases\ProblemCaseResource;
use App\Modules\Merchants\Models\ProblemCase;
use Illuminate\Http\Request;

class ProblemCasesController extends ApiBaseController
{
    public function index(Request $request)
    {
        $problemCases = ProblemCase::query()->filterRequests($request);

        if ($request->query('object') == true) {
            $problemCases->first();
        }

        if ($request->query('paginate') == false) {
            $problemCases->get();
        }

        return ProblemCaseResource::collection($problemCases->paginate($request->query('per_page') ?? 15));
    }

}
