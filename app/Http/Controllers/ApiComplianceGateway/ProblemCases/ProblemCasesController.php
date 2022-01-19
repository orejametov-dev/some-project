<?php

namespace App\Http\Controllers\ApiComplianceGateway\ProblemCases;

use App\Exceptions\ApiBusinessException;
use App\Http\Controllers\ApiComplianceGateway\ApiBaseController;
use App\Http\Requests\ApiPrm\Merchants\ProblemCases\ProblemCaseStoreRequest;
use App\HttpServices\Core\CoreService;
use App\HttpServices\Hooks\DTO\HookData;
use App\Jobs\SendHook;
use App\Jobs\SendSmsJob;
use App\Modules\Merchants\DTO\ProblemCases\ProblemCaseDTO;
use App\Modules\Merchants\Models\ProblemCase;
use App\Modules\Merchants\Services\ProblemCases\ProblemCaseService;
use App\Services\SMS\SmsMessages;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProblemCasesController extends ApiBaseController
{
    public function store(ProblemCaseStoreRequest $request, ProblemCaseService $problemCaseService)
    {
        $data = CoreService::getApplicationDataByApplicationId($request->input('application_id'));

        if (ProblemCase::query()->where('application_id', $request->input('application_id'))
            ->where('status_id', '!=', ProblemCase::FINISHED)
            ->orderByDesc('id')->exists()) {
            throw new ApiBusinessException('На данную заявку был уже создан проблемный кейс', 'problem_case_exist', [
                'ru' => 'На данную заявку был уже создан проблемный кейс',
                'uz' => 'Bu arizaga tegishli muammoli keys avval yuborilgan.'
            ], 400);
        }

        $problemCase = $problemCaseService->create((new ProblemCaseDTO())->fromProblemCaseRequest($request, $data, "COMPLIANCE", $this->user));

        $problemCase->setStatusNew();
        $problemCase->save();

        SendHook::dispatch(new HookData(
            service: 'merchants',
            hookable_type: $problemCase->getTable(),
            hookable_id: $problemCase->id,
            created_from_str: 'COMPLIANCE',
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

}
