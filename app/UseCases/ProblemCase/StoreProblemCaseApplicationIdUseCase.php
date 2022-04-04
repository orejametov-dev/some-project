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
use http\Exception\InvalidArgumentException;

class StoreProblemCaseApplicationIdUseCase extends AbstractStoreProblemCaseUseCase
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
        if (ProblemCase::query()->where('application_id', $identifier)
            ->where('status_id', '!=', ProblemCaseStatusEnum::FINISHED())
            ->orderByDesc('id')->exists()) {
            throw new ApiBusinessException('На данную заявку был уже создан проблемный кейс', 'problem_case_exist', [
                'ru' => 'На данную заявку был уже создан проблемный кейс',
                'uz' => 'Bu arizaga tegishli muammoli keys avval yuborilgan.',
            ], 400);
        }
    }

    protected function setIdentifierNumberAndDate(ProblemCase $problemCase, string|int $identifier, mixed $data): void
    {
        if (is_int($identifier) === false) {
            throw new InvalidArgumentException('identifier should be string');
        }

        $problemCase->application_id = $identifier;
        $problemCase->application_created_at = $data->application_created_at;
    }

    protected function getDataByIdentifier(int|string $identifier): mixed
    {
        if (is_int($identifier) === false) {
            throw new InvalidArgumentException('identifier should be string');
        }

        $data = $this->coreHttpRepository->getApplicationDataByApplicationId($identifier);

        if ($data === null) {
            throw new BusinessException('Заявка не была найдена', 'object_not_found', 404);
        }

        return $data;
    }
}
