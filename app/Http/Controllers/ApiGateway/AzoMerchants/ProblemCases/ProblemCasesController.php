<?php


namespace App\Http\Controllers\ApiGateway\AzoMerchants\ProblemCases;


use App\Http\Controllers\ApiGateway\ApiBaseController;
use App\Http\Requests\ApiPrm\Merchants\ProblemCases\ProblemCaseStoreRequest;
use App\Http\Requests\ApiPrm\Merchants\ProblemCases\ProblemCaseUpdateRequest;
use App\HttpServices\Hooks\DTO\HookData;
use App\Jobs\SendHook;
use App\HttpServices\Core\CoreService;
use App\Modules\Merchants\DTO\ProblemCases\ProblemCaseDTO;
use App\Modules\Merchants\Models\ProblemCase;
use App\Modules\Merchants\Models\ProblemCaseTag;
use App\Modules\Merchants\Services\ProblemCases\ProblemCaseService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProblemCasesController extends ApiBaseController
{
    public function index(Request $request)
    {
        $problemCases = ProblemCase::with('tags')
            ->filterRequests($request)
            ->orederByDesc('created_at');

        return $problemCases->paginate($request->query('per_page') ?? 15);
    }

    public function store(ProblemCaseStoreRequest $request, ProblemCaseService $problemCaseService)
    {
        if ($request->has('credit_number') and $request->input('credit_number')) {
            $data = CoreService::getApplicationDataByContractNumber($request->input('credit_number'));
        } elseif ($request->has('application_id') and $request->input('application_id')) {
            $data = CoreService::getApplicationDataByApplicationId($request->input('application_id'));
        }+

        $problemCase = $problemCaseService->create((new ProblemCaseDTO())->fromProblemCaseRequest($request,$data));

        SendHook::dispatch(new HookData(
            service: 'merchants',
            hookable_type: $problemCase->getTable(),
            hookable_id: $problemCase->id,
            created_from_str: 'PRM',
            created_by_id: $this->user->id,
            body: 'Создан проблемный кейс co статусом',
            keyword: ProblemCase::$statuses[$problemCase->status_id]['name'],
            action: 'create',
            class: 'info',
            action_at: null,
            created_by_str: $this->user->name,
        ));

        return $problemCase;
    }

    public function show($id)
    {
        $problemCase = ProblemCase::findOrFail($id);

        return $problemCase;
    }

    public function update($id, ProblemCaseUpdateRequest $request)
    {
        $problemCase = ProblemCase::findOrFail($id);
        $problemCase->manager_comment = $request->input('manager_comment');
        $problemCase->merchant_comment = $request->input('merchant_comment');
        $problemCase->deadline = $request->input('deadline');

        $problemCase->save();

        return $problemCase;
    }

    public function attachTags(Request $request, $id)
    {
        $request->validate([
            'tags' => 'required|array',
            'tags.*.name' => 'required|string',
            'tags.*.type_id' => 'required|integer|in:' . ProblemCaseTag::BEFORE_TYPE .', '. ProblemCaseTag::AFTER_TYPE
        ]);

        $problemCase = ProblemCase::findOrFail($id);
        $problemCase->tags()->detach();
        $tags = [];
        foreach ($request->input('tags') as $item) {
            $tag = ProblemCaseTag::query()->firstOrCreate(['body' => $item['name'], 'type_id' => $item['type_id']]);
            $tags[] = $tag->id;
        }
        $problemCase->tags()->attach($tags);


        return response()->json($problemCase->load('tags'));
    }

    public function setStatus(Request $request, $id)
    {
        $this->validate($request, [
            'status_id' => 'required|integer|in:'
                . ProblemCase::NEW . ','
                . ProblemCase::IN_PROCESS . ','
                . ProblemCase::DONE . ','
                . ProblemCase::FINISHED
        ]);
        $problemCase = ProblemCase::findOrFail($id);
        $problemCase->setStatus($request->input('status_id'));
        $problemCase->save();

        SendHook::dispatch(new HookData(
            service: 'merchants',
            hookable_type: $problemCase->getTable(),
            hookable_id: $problemCase->id,
            created_from_str: 'PRM',
            created_by_id: $this->user->id,
            body: 'Обновлен на статус',
            keyword: ProblemCase::$statuses[$problemCase->status_id]['name'],
            action: 'update',
            class: 'info',
            action_at: null,
            created_by_str: $this->user->name,
        ));

        return $problemCase;
    }

    public function getProblemCasesOfMerchantUser($user_id,Request $request)
    {
        $problemCases = ProblemCase::query()->with('tags', function($query) {
            $query->where('type_id', 2);
        })->where('created_by_id', $user_id)
            ->orderByDesc('id');

        return $problemCases->paginate($request->query('per_page') ?? 15);
    }

}
