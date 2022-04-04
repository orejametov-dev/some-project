<?php

declare(strict_types=1);

namespace App\UseCases\ProblemCase;

use Alifuz\Utils\Gateway\Entities\Auth\GatewayAuthUser;
use Alifuz\Utils\Gateway\Entities\GatewayApplication;
use App\Enums\ProblemCaseStatusEnum;
use App\HttpRepositories\Hooks\DTO\HookData;
use App\Jobs\SendHook;
use App\Jobs\SendSmsJob;
use App\Mappings\ProblemCaseStatusMapping;
use App\Models\ProblemCase;
use App\Services\SMS\SmsMessages;

class SetStatusProblemCaseUseCase
{
    public function __construct(
        private GatewayAuthUser $gatewayAuthUser,
        private ProblemCaseStatusMapping $problemCaseStatusMapping,
        private FindProblemCaseByIdUseCase $findProblemCaseByIdUseCase,
        private GatewayApplication $gatewayApplication,
    ) {
    }

    public function execute(int $id, int $status_id): ProblemCase
    {
        $problemCase = $this->findProblemCaseByIdUseCase->execute($id);

        $problemCase->setStatus(ProblemCaseStatusEnum::from($status_id));
        $problemCase->save();

        if ($problemCase->isStatusFinished()) {
            $message = SmsMessages::onFinishedProblemCases($problemCase->client_name . ' ' . $problemCase->client_surname, $problemCase->id);
            SendSmsJob::dispatch($problemCase->phone, $message);
        }

        SendHook::dispatch(new HookData(
            service: 'merchants',
            hookable_type: $problemCase->getTable(),
            hookable_id: $problemCase->id,
            created_from_str: $this->gatewayApplication->getApplication()->getValue(),
            created_by_id: $this->gatewayAuthUser->getId(),
            body: 'Обновлен на статус',
            keyword: $this->problemCaseStatusMapping->getMappedValue($problemCase->status_id)['name'],
            action: 'update',
            class: 'info',
            action_at: null,
            created_by_str: $this->gatewayAuthUser->getName(),
        ));

        return $problemCase;
    }
}
