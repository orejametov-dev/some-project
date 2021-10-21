<?php


namespace App\Http\Controllers\ApiMerchantGateway\ProblemCases;


use App\Http\Controllers\ApiMerchantGateway\ApiBaseController;
use App\Http\Resources\ApiMerchantGateway\ProblemCases\ProblemCaseResource;
use App\HttpServices\Hooks\DTO\HookData;
use App\Jobs\SendHook;
use App\Modules\Merchants\Models\ProblemCase;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
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

    public function setCommentFromMerchant($id, Request $request)
    {
        $this->validate($request, [
            'body' => 'string|required'
        ]);

        $problemCase = ProblemCase::findOrFail($id);
        $problemCase->comment_from_merchant = $request->input('body');
        $problemCase->save();

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

        SendHook::dispatch(new HookData(
            service: 'merchants',
            hookable_type: $problemCase->getTable(),
            hookable_id: $problemCase->id,
            created_from_str: 'MERCHANT',
            created_by_id: $this->user->id,
            body: 'Обновлен на статус',
            keyword: ProblemCase::$statuses[$problemCase->status_id]['name'],
            action: 'create',
            class: 'info',
            action_at: null,
            created_by_str: $this->user->name,
        ));


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

    public function getStatuses()
    {
        return array_values(ProblemCase::$statuses);
    }

    public function getNewProblemCasesCounter(Request $request)
    {
        $counter =  Cache::remember('new-problem-cases-counter_' . $this->merchant_id, 10 * 60, function () use ($request) {
            return ProblemCase::query()->byMerchant($this->merchant_id)
                ->filterRequests($request)->onlyNew()->count();
        });

        return response()->json(['count' => $counter]);
    }

}
