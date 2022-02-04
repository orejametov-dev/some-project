<?php

namespace App\UseCases\ProblemCase;

use Alifuz\Utils\Gateway\Entities\Auth\GatewayAuthUser;
use Alifuz\Utils\Gateway\Entities\GatewayApplication;
use App\Exceptions\ApiBusinessException;
use App\Exceptions\BusinessException;
use App\HttpRepositories\Core\CoreHttpRepository;
use App\Modules\Merchants\Models\ProblemCase;
use Carbon\Carbon;

class StoreProblemCaseApplicationIdUseCase extends AbstractStoreProblemCaseUseCase
{
    public function __construct(
        private CoreHttpRepository $coreHttpRepository,
        private GatewayAuthUser    $gatewayAuthUser,
        private GatewayApplication $gatewayApplication
    )
    {
        parent::__construct(
            $this->gatewayApplication,
            $this->gatewayAuthUser
        );
    }

    protected function checkStatusToFinished(string|int $identifier): void
    {
        if (ProblemCase::query()->where('application_id', $identifier)
            ->where('status_id', '!=', ProblemCase::FINISHED)
            ->orderByDesc('id')->exists()) {
            throw new ApiBusinessException('На данную заявку был уже создан проблемный кейс', 'problem_case_exist', [
                'ru' => 'На данную заявку был уже создан проблемный кейс',
                'uz' => 'Bu arizaga tegishli muammoli keys avval yuborilgan.'
            ], 400);
        }
    }

    protected function setIdentifierNumberAndDate(ProblemCase $problemCase, $identifier_number, $data)
    {
        $problemCase->application_id = $identifier_number;
        $problemCase->application_created_at = Carbon::parse($data->application_created_at)->format('Y-m-d');
    }

    protected function getDataByIdentifier(int|string $identifier): mixed
    {
        $data = $this->coreHttpRepository->getApplicationDataByApplicationId($identifier);

        if ($data === null) {
            throw new BusinessException('Заявка не была найдена', 'object_not_found', 404);
        }

        return $data;
    }
}
