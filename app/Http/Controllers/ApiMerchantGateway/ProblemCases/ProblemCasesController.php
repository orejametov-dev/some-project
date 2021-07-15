<?php


namespace App\Http\Controllers\ApiMerchantGateway\ProblemCases;


use App\Http\Controllers\ApiMerchantGateway\ApiBaseController;
use App\Http\Resources\ApiMerchantGateway\ProblemCases\ProblemCaseResource;
use App\Modules\Merchants\Models\ProblemCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProblemCasesController extends ApiBaseController
{
    public function index(Request $request)
    {
        $problemCases = ProblemCase::query()->with('before_tags')
            ->byMerchant($this->merchant_id)
            ->filterRequests($request);

        if ($request->query('object') == true) {
            $problemCases->first();
        }

        return ProblemCaseResource::collection($problemCases->paginate($request->query('per_page') ?? 15));
    }

    public function show($id, Request $request)
    {
        $problemCase = ProblemCase::with('before_tags')
            ->filterRequests($request)
            ->findOrFail($id);

        return new ProblemCaseResource($problemCase);
    }

    public function setStatus($id, Request $request)
    {
        $this->validate($request, [
            'status_id' => 'required|integer'
        ]);

        $problemCase = ProblemCase::findOrFail($id);
        $problemCase->setStatus($request->input('status_id'));

        $problemCase->save();

        return $problemCase;
    }

    public function setEngage($id)
    {
        $problemCase = ProblemCase::findOrFail($id);

        $problemCase->engaged_by_id = $this->user->id;
        $problemCase->engaged_by_name = $this->user->name;
        $problemCase->engaged_at = now();

        $problemCase->save();

        return new ProblemCaseResource($problemCase);
    }

    public function getStatuses()
    {
        return array_values(ProblemCase::$statuses);
    }

    public function getNewProblemCasesCounter(Request $request)
    {
        $counter =  Cache::remember('new-problem-cases-counter_' . $request->merchant_id, 10 * 60, function () use ($request) {
            return ProblemCase::filterRequests($request)->onlyNew()->count();
        });

        return response()->json(['count' => $counter]);
    }

}
