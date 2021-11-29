<?php


namespace App\Http\Controllers\ApiGateway\AzoMerchants\ProblemCases;


use App\Exceptions\ApiBusinessException;
use App\Http\Controllers\ApiGateway\ApiBaseController;
use App\HttpServices\Core\CoreService;
use App\HttpServices\Hooks\DTO\HookData;
use App\HttpServices\Notify\NotifyMicroService;
use App\Jobs\SendHook;
use App\Modules\Merchants\Models\ProblemCase;
use App\Modules\Merchants\Models\ProblemCaseTag;
use App\Services\SMS\SmsMessages;
use Carbon\Carbon;
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

    public function store(Request $request)
    {
        $this->validate($request, [
            'created_from_name' => 'required|string',
            'credit_number' => 'required_without:application_id|string',
            'application_id' => 'required_without:credit_number|integer',
            'assigned_to_id' => 'required|integer',
            'assigned_to_name' => 'required|string',
            'search_index' => 'required|string',
        ]);
        $problemCase = new ProblemCase();

        if ($request->has('credit_number') and $request->input('credit_number')) {
            $data = CoreService::getApplicationDataByContractNumber($request->input('credit_number'));

            if ($problem_case = ProblemCase::query()->where('credit_number', $request->input('credit_number'))->orderByDesc('id')->first()) {
                if ($problem_case->status_id != ProblemCase::FINISHED) {
                    throw new ApiBusinessException('На данный кредитный номер был уже создан проблемный кейс', 'problem_case_exist', [
                        'ru' => "На данный кредитный номер был уже создан проблемный кейс",
                        'uz' => 'Bu kredit raqami uchun muammoli holat allaqachon yaratilgan'
                    ], 400);
                }
            }

            $problemCase->credit_number = $request->input('credit_number');
            $problemCase->credit_contract_date = $data['contract_date'];
        } elseif ($request->has('application_id') and $request->input('application_id')) {
            $data = CoreService::getApplicationDataByApplicationId($request->input('application_id'));

            if ($problem_case = ProblemCase::query()->where('application_id', $request->input('application_id'))->orderByDesc('id')->first()) {
                if ($problem_case->status_id != ProblemCase::FINISHED) {
                    throw new ApiBusinessException('На данный кредитный номер был уже создан проблемный кейс', 'problem_case_exist', [
                        'ru' => 'На данный кредитный номер был уже создан проблемный кейс',
                        'uz' => 'Bu kredit raqami uchun muammoli holat allaqachon yaratilgan'
                    ], 400);
                }
            }

            $problemCase->application_id = $request->input('application_id');
            $problemCase->application_created_at = Carbon::parse($data['created_at'])->format('Y-m-d');

        }

        $problemCase->merchant_id = $data['merchant_id'];
        $problemCase->store_id = $data['store_id'];
        $problemCase->client_id = $data['client']['id'];

        $problemCase->search_index = $data['client']['name']
            . ' ' . $data['client']['surname']
            . ' ' . $data['client']['patronymic']
            . ' ' . $data['client']['phone'];

        $problemCase->application_items = $data['application_items'];

        $problemCase->created_by_id = $data['merchant_engaged_by']['id'];
        $problemCase->created_by_name = $data['merchant_engaged_by']['name'];
        $problemCase->created_from_name = $request->input('created_from_name');

        $problemCase->assigned_to_id = $request->input('assigned_to_id');
        $problemCase->assigned_to_name = $request->input('assigned_to_name');
        $problemCase->description = $request->input('description');

        $problemCase->setStatusNew();
        $problemCase->save();

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
        $problemCase = ProblemCase::with('tags')->findOrFail($id);

        return $problemCase;
    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            'manager_comment' => 'nullable|string',
            'merchant_comment' => 'nullable|string',
            'deadline' => 'nullable|date_format:Y-m-d',
        ]);

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

        if ($problemCase->status_id === ProblemCase::FINISHED) {
            preg_match("/" . preg_quote("9989") . "(.*)/", $problemCase->search_index, $phone);

            if (!empty($phone)) {
                $message = SmsMessages::onFinishedProblemCases();
                NotifyMicroService::sendSms(array_shift($phone), $message);
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

    public function getProblemCasesOfMerchantUser($user_id, Request $request)
    {
        $problemCases = ProblemCase::query()->with('tags', function ($query) {
            $query->where('type_id', 2);
        })->where('created_by_id', $user_id)
            ->orderByDesc('id');

        return $problemCases->paginate($request->query('per_page') ?? 15);
    }

}
