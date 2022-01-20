<?php

namespace App\Http\Controllers\ApiLawGateway\ProblemCases;

use App\Exceptions\ApiBusinessException;
use App\Http\Controllers\ApiLawGateway\ApiBaseController;
use App\Http\Requests\ApiPrm\Merchants\ProblemCases\ProblemCaseStoreRequest;
use App\HttpServices\Core\CoreService;
use App\HttpServices\Hooks\DTO\HookData;
use App\Jobs\SendHook;
use App\Jobs\SendSmsJob;
use App\Modules\Merchants\DTO\ProblemCases\ProblemCaseDTO;
use App\Modules\Merchants\Models\ProblemCase;
use App\Modules\Merchants\Services\ProblemCases\ProblemCaseService;
use App\Services\SMS\SmsMessages;
use App\UseCases\ApiLawGateway\ProblemCases\StoreProblemCasesUseCase;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProblemCasesController extends ApiBaseController
{
    public function store(ProblemCaseStoreRequest $request, StoreProblemCasesUseCase $storeProblemCasesUseCase)
    {
        $problemCaseDTO = new ProblemCaseDTO(
            created_from_name: (string) "LAW",
            description: (string) $request->input('description'),
            credit_number: $request->input('credit_number')
        );

        return $storeProblemCasesUseCase->execute($problemCaseDTO, $this->user);
    }
}
