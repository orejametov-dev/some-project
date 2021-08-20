<?php


namespace App\Http\Controllers\ApiCallsGateway\ProblemCases;


use App\Http\Controllers\Controller;
use App\Http\Resources\ApiCallsGateway\ProblemCases\ProblemCaseResource;
use App\Modules\Merchants\Models\ProblemCase;
use Illuminate\Http\Request;

class ProblemCasesController extends Controller
{
    public function index(Request $request)
    {
        $problemCases = ProblemCase::query()->filterRequests($request);

        if ($request->query('object') == true) {
            return new ProblemCaseResource($problemCases->first());
        }

        return ProblemCaseResource::collection($problemCases->paginate($request->query('per_page') ?? 15));
    }

    public function getStatusList()
    {
        return array_values(ProblemCase::$statuses);
    }

}
