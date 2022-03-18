<?php

declare(strict_types=1);

namespace App\UseCases\ProblemCase;

use Alifuz\Utils\Gateway\Entities\Auth\GatewayAuthUser;
use Alifuz\Utils\Gateway\Entities\GatewayApplication;
use App\Enums\ProblemCaseStatusEnum;
use App\Exceptions\ApiBusinessException;
use App\Exceptions\BusinessException;
use App\HttpRepositories\Core\CoreHttpRepository;
use App\Mappings\ProblemCaseStatusMapping;
use App\Models\ProblemCase;

class StoreProblemCaseNumberCreditUseCase extends AbstractStoreProblemCaseUseCase
{
    public function __construct(
        private CoreHttpRepository $coreHttpRepository,
        private GatewayAuthUser $gatewayAuthUser,
        private GatewayApplication $gatewayApplication,
        private ProblemCaseStatusMapping $problemCaseStatusMapping
    ) {
        parent::__construct(
            $this->gatewayApplication,
            $this->gatewayAuthUser,
            $this->problemCaseStatusMapping
        );
    }

    protected function checkStatusToFinished(string|int $identifier): void
    {
        if (ProblemCase::query()->where('credit_number', $identifier)
                ->where('status_id', '!=', ProblemCaseStatusEnum::FINISHED())
                ->orderByDesc('id')->exists()) {
            throw new ApiBusinessException('На данный кредитный номер был уже создан проблемный кейс', 'problem_case_exist', [
                    'ru' => 'На данный кредитный номер был уже создан проблемный кейс',
                    'uz' => 'Bu kredit raqamiga tegishli muammoli keys avval yuborilgan.',
                ], 400);
        }
    }

    protected function getDataByIdentifier(int|string $identifier): mixed
    {
        $data = $this->coreHttpRepository->getApplicationDataByContractNumber($identifier);

        if ($data === null) {
            throw new BusinessException('Кредит не был найден', 'object_not_found', 404);
        }

        return $data;
    }

    protected function setIdentifierNumberAndDate(ProblemCase $problemCase, int|string $identifier_number, mixed $data) : void
    {
        $problemCase->credit_number = $identifier_number;
        $problemCase->credit_contract_date = $data->credit_contract_date;
    }
}
