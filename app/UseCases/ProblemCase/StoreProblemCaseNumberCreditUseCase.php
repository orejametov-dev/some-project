<?php

namespace App\UseCases\ProblemCase;

use App\Exceptions\ApiBusinessException;
use App\HttpRepositories\CoreHttpRepositories\CoreHttpRepository;
use App\Modules\Merchants\Models\ProblemCase;

class StoreProblemCaseNumberCreditUseCase extends AbstractStoreProblemCaseUseCase
{
    public function __construct(
        private CoreHttpRepository $coreHttpRepository
    )
    {
    }

    protected function checkStatusToFinished(string|int $identifier): void
    {
            if (ProblemCase::query()->where('credit_number', $identifier)
                ->where('status_id', '!=', ProblemCase::FINISHED)
                ->orderByDesc('id')->exists()) {
                throw new ApiBusinessException('На данный кредитный номер был уже создан проблемный кейс', 'problem_case_exist', [
                    'ru' => "На данный кредитный номер был уже создан проблемный кейс",
                    'uz' => 'Bu kredit raqamiga tegishli muammoli keys avval yuborilgan.'
                ], 400);
            }
    }

    protected function getDataByIdentifier(int|string $identifier): mixed
    {
        return $this->coreHttpRepository->getApplicationDataByContractNumber($identifier);
    }

    protected function setIdentifierNumberAndDate(ProblemCase $problemCase,$identifier_number , $data)
    {
            $problemCase->credit_number = $identifier_number;
            $problemCase->credit_contract_date = $data->credit_contract_date;
    }
}
