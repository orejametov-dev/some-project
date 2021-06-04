<?php


namespace App\Http\Controllers\ApiMerchantGateway\ProblemCases;


use App\Http\Controllers\ApiMerchantGateway\ApiBaseController;
use App\Http\Resources\ApiMerchantGateway\ProblemCases\ProblemCaseResource;
use App\Modules\Merchants\Models\ProblemCase;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProblemCasesController extends ApiBaseController
{
    public function index(Request $request)
    {
        $problemCases = ProblemCase::query()->with('before_tags')->filterRequests($request);

        if ($request->query('object') == true) {
            $problemCases->first();
        }

        if ($request->query('paginate') == false) {
            $problemCases->get();
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

    public function setStatusInProcess($id)
    {
        $problemCase = ProblemCase::findOrFail($id);

        if (!$problemCase->isStatusNew()) {
            return response()->json(['message' => 'Статус должен быть только новым'], 400);
        }
        $problemCase->setStatusInProcess();
        $problemCase->save();

        return new ProblemCaseResource($problemCase);
    }

    public function setStatusDone($id)
    {

        $problemCase = ProblemCase::findOrFail($id);

        if (!$problemCase->isStatusDone()) {
            return response()->json(['message' => 'Статус должен быть только в процессе'], 400);
        }

        $problemCase->setStatusDone();
        $problemCase->save();

        return new ProblemCaseResource($problemCase);
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

}
