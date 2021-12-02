<?php


namespace App\Http\Controllers\ApiGateway\AzoMerchants\ProblemCases;


use App\Http\Controllers\ApiGateway\ApiBaseController;
use App\HttpServices\Hooks\DTO\HookData;
use App\HttpServices\Notify\NotifyMicroService;
use App\Jobs\SendHook;
use App\Modules\Merchants\DTO\Comments\CommentDTO;
use App\Modules\Merchants\Models\Comment;
use App\Modules\Merchants\Models\ProblemCase;
use App\Modules\Merchants\Models\ProblemCaseTag;
use App\Modules\Merchants\Services\Comments\CommentService;
use App\Services\SMS\SmsMessages;
use Arr;
use Illuminate\Http\Request;

class ProblemCasesController extends ApiBaseController
{
    public function index(Request $request)
    {
        $problemCases = ProblemCase::with('tags')
            ->filterRequests($request)
            ->orderBy('created_at', 'DESC');

        if ($request->has('object') and $request->query('object') == true) {
            return $problemCases->first();
        }

        if ($request->has('paginate') and $request->query('paginate') == false) {
            return $problemCases->get();
        }
        return $problemCases->paginate($request->query('per_page') ?? 15);
    }

    public function show($id)
    {
        $problemCase = ProblemCase::with('tags')->findOrFail($id);

        return $problemCase;
    }

    public function update($id, Request $request, CommentService $commentService)
    {
        $this->validate($request, [
            'manager_comment' => 'nullable|string',
            'merchant_comment' => 'nullable|string',
            'deadline' => 'nullable|date_format:Y-m-d',
        ]);

        $problemCase = ProblemCase::findOrFail($id);
        $problemCase->deadline = $request->input('deadline');

        if (!empty($request->input('manager_comment'))) {
            $commentService->create(new CommentDTO(
                commentable_type: Comment::PROBLEM_CASE_FOR_PRM,
                commentable_id: $problemCase->id,
                body: $request->input('manager_comment'),
                created_by_id: $this->user->id,
                created_by_name: $this->user->name
            ));
        }

        if (!empty($request->input('merchant_comment'))) {
            $commentService->create(new CommentDTO(
                commentable_type: Comment::PROBLEM_CASE_FOR_MERCHANT,
                commentable_id: $problemCase->id,
                body: $request->input('merchant_comment'),
                created_by_id: $this->user->id,
                created_by_name: $this->user->name
            ));
        }

        $problemCase->save();

        return $problemCase;
    }

    public function attachTags(Request $request, $id)
    {
        $request->validate([
            'tags' => 'required|array',
            'tags.*.name' => 'required|string',
            'tags.*.type_id' => 'required|integer|in:' . ProblemCaseTag::BEFORE_TYPE . ', ' . ProblemCaseTag::AFTER_TYPE
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
        $problemCase = ProblemCase::query()->findOrFail($id);
        $problemCase->setStatus($request->input('status_id'));
        $problemCase->save();

        if ($problemCase->isStatusFinished()) {
            preg_match("/" . preg_quote("9989") . "(.*)/", $problemCase->search_index, $phone);
            $name = explode('9989', $problemCase->search_index);

            if (!empty($phone)) {
                $message = SmsMessages::onFinishedProblemCases(Arr::first($name), $problemCase->id);
                NotifyMicroService::sendSms(Arr::first($phone), $message);
            }
        }

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

    public function setAssigned($id, Request $request)
    {
        $request->validate([
            'assigned_to_id' => 'required|integer',
            'assigned_to_name' => 'required|string',
        ]);

        $problemCase = ProblemCase::query()->findOrFail($id);
        $problemCase->assigned_to_id = $request->input('assigned_to_id');
        $problemCase->assigned_to_name = $request->input('assigned_to_name');
        $problemCase->save();

        return $problemCase;
    }

    public function getProblemCasesOfMerchantUser($user_id, Request $request)
    {
        $problemCases = ProblemCase::query()->with('tags', function ($query) {
            $query->where('type_id', 2);
        })->where('created_by_id', $user_id)
            ->orderByDesc('id');

        return $problemCases->paginate($request->query('per_page') ?? 15);
    }

}
