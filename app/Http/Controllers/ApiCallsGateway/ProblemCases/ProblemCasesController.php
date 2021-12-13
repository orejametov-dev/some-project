<?php


namespace App\Http\Controllers\ApiCallsGateway\ProblemCases;


use App\Exceptions\ApiBusinessException;
use App\Http\Controllers\ApiCallsGateway\ApiBaseController;
use App\Http\Resources\ApiCallsGateway\ProblemCases\ProblemCaseResource;
use App\HttpServices\Core\CoreService;
use App\HttpServices\Hooks\DTO\HookData;
use App\HttpServices\Notify\NotifyMicroService;
use App\Jobs\SendHook;
use App\Jobs\SendSmsJob;
use App\Modules\Merchants\Models\ProblemCase;
use App\Services\SMS\SmsMessages;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProblemCasesController extends ApiBaseController
{
    public function index(Request $request)
    {
        $problemCases = ProblemCase::query()->filterRequests($request);

        if ($request->query('object') == true) {
            return new ProblemCaseResource($problemCases->first());
        }

        return ProblemCaseResource::collection($problemCases->paginate($request->query('per_page') ?? 15));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'credit_number' => 'required_without:application_id|string',
            'application_id' => 'required_without:credit_number|integer',
            'description' => 'required'
        ]);

        $problemCase = new ProblemCase();

        if ($request->has('credit_number') and $request->input('credit_number')) {
            $data = CoreService::getApplicationDataByContractNumber($request->input('credit_number'));

            if (ProblemCase::query()->where('credit_number', $request->input('credit_number'))
                ->where('status_id', '!=', ProblemCase::FINISHED)
                ->orderByDesc('id')->exists()) {
                throw new ApiBusinessException('На данный кредитный номер был уже создан проблемный кейс', 'problem_case_exist', [
                    'ru' => "На данный кредитный номер был уже создан проблемный кейс",
                    'uz' => 'Bu kredit raqamiga tegishli muammoli keys avval yuborilgan.'
                ], 400);
            }

            $problemCase->credit_number = $request->input('credit_number');
            $problemCase->credit_contract_date = $data['contract_date'];
        } elseif ($request->has('application_id') and $request->input('application_id')) {
            $data = CoreService::getApplicationDataByApplicationId($request->input('application_id'));

            if (ProblemCase::query()->where('application_id', $request->input('application_id'))
                ->where('status_id', '!=', ProblemCase::FINISHED)
                ->orderByDesc('id')->exists()) {
                throw new ApiBusinessException('На данную заявку был уже создан проблемный кейс', 'problem_case_exist', [
                    'ru' => 'На данную заявку был уже создан проблемный кейс',
                    'uz' => 'Bu arizaga tegishli muammoli keys avval yuborilgan.'
                ], 400);
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

        $problemCase->client_name = $data['client']['name'];
        $problemCase->client_surname = $data['client']['surname'];
        $problemCase->client_patronymic = $data['client']['patronymic'];
        $problemCase->phone = $data['client']['phone'];

        $problemCase->application_items = $data['application_items'];

        $problemCase->post_or_pre_created_by_id = $data['merchant_engaged_by']['id'];
        $problemCase->post_or_pre_created_by_name = $data['merchant_engaged_by']['name'];

        $problemCase->created_from_name = "CALLS";
        $problemCase->created_by_id = $this->user->id;
        $problemCase->created_by_name = $this->user->name;
        $problemCase->description = $request->input('description');

        $problemCase->setStatusNew();
        $problemCase->save();

        $message = SmsMessages::onNewProblemCases($problemCase->client_name . ' ' . $problemCase->client_surname, $problemCase->id);
        NotifyMicroService::sendSms($problemCase->phone, $message, NotifyMicroService::PROBLEM_CASE);

        SendHook::dispatch(new HookData(
            service: 'merchants',
            hookable_type: $problemCase->getTable(),
            hookable_id: $problemCase->id,
            created_from_str: 'CALLS',
            created_by_id: $this->user->id,
            body: 'Создан проблемный кейс co статусом',
            keyword: ProblemCase::$statuses[$problemCase->status_id]['name'],
            action: 'create',
            class: 'info',
            action_at: null,
            created_by_str: $this->user->name,
        ));

        $message = SmsMessages::onNewProblemCases($problemCase->client_name . ' ' . $problemCase->client_surname, $problemCase->id);
        SendSmsJob::dispatch($problemCase->phone, $message);

        return $problemCase;
    }


    public function getStatusList()
    {
        return array_values(ProblemCase::$statuses);
    }

}
