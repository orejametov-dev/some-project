<?php

namespace App\UseCases\ProblemCase;

use App\Exceptions\ApiBusinessException;
use App\HttpRepositories\Core\CoreHttpRepository;
use App\Modules\Merchants\Models\ProblemCase;

class StoreProblemCaseApplicationIdUseCase extends AbstractStoreProblemCaseUseCase
{
    public function __construct(
        private CoreHttpRepository $coreHttpRepository
    )
    {
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

    protected function setIdentifierNumberAndDate(ProblemCase $problemCase ,$identifier_number, $data)
    {
        $problemCase->application_id = $identifier_number;
        $problemCase->application_created_at = $data->application_created_at;
    }

    protected function getDataByIdentifier(int|string $identifier): mixed
    {
        return $this->coreHttpRepository->getApplicationDataByApplicationId($identifier);
    }
}
