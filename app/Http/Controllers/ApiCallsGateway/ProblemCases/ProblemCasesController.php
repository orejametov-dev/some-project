<?php


namespace App\Http\Controllers\ApiCallsGateway\ProblemCases;


use App\Exceptions\ApiBusinessException;
use App\Http\Controllers\ApiCallsGateway\ApiBaseController;
use App\Http\Resources\ApiCallsGateway\ProblemCases\ProblemCaseResource;
use App\HttpServices\Core\CoreService;
use App\HttpServices\Hooks\DTO\HookData;
use App\HttpServices\Notify\NotifyMicroService;
use App\Jobs\SendHook;
use App\Modules\Merchants\DTO\ProblemCases\ProblemCaseDTO;
use App\Jobs\SendSmsJob;
use App\Modules\Merchants\Models\ProblemCase;
use App\Modules\Merchants\Services\ProblemCases\ProblemCaseService;
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

    public function store(Request $request, ProblemCaseService $problemCaseService)
    {
        $this->validate($request, [
            'credit_number' => 'required_without:application_id|string',
            'application_id' => 'required_without:credit_number|integer',
            'description' => 'required'
        ]);

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
        }

        $problemCase = $problemCaseService->create((new ProblemCaseDTO())->fromProblemCaseRequest($request,$data));

        $problemCase->setStatusNew();
        $problemCase->save();


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
