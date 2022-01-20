<?php


namespace App\Http\Controllers\ApiCallsGateway\ProblemCases;


use App\Exceptions\ApiBusinessException;
use App\Http\Controllers\ApiCallsGateway\ApiBaseController;
use App\Http\Requests\ApiPrm\Merchants\ProblemCases\ProblemCaseStoreRequest;
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
use App\UseCases\ApiCallsGateway\ProblemCases\IndexProblemCasesUseCase;
use App\UseCases\ApiCallsGateway\ProblemCases\StoreProblemCasesUseCase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use function GuzzleHttp\Psr7\str;

class ProblemCasesController extends ApiBaseController
{
    public function index(Request $request, IndexProblemCasesUseCase $indexProblemCasesUseCase)
    {
        return $indexProblemCasesUseCase->execute($request);
    }

    public function store(ProblemCaseStoreRequest $request, StoreProblemCasesUseCase $storeProblemCasesUseCase)
    {
        $problemCaseDTO = new ProblemCaseDTO(
            created_from_name: (string)"CALLS",
            description: (string)$request->input('description'),
            credit_number: $request->input('credit_number')
        );

        return $storeProblemCasesUseCase->execute($problemCaseDTO, $this->user);
    }


    public function getStatusList()
    {
        return array_values(ProblemCase::$statuses);
    }

}
