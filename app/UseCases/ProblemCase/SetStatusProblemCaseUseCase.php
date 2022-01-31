<?php

namespace App\UseCases\ProblemCase;

use Alifuz\Utils\Gateway\Entities\Auth\GatewayAuthUser;
use App\Exceptions\BusinessException;
use App\HttpServices\Hooks\DTO\HookData;
use App\Jobs\SendHook;
use App\Jobs\SendSmsJob;
use App\Modules\Merchants\Models\ProblemCase;
use App\Services\SMS\SmsMessages;

class SetStatusProblemCaseUseCase
{
    public function __construct(
        private GatewayAuthUser $gatewayAuthUser
    )
    {
    }

    public function execute(int $id , int $status_id): ProblemCase
    {
        $problemCase = ProblemCase::query()->find($id);

        if ($problemCase === null) {
            throw new BusinessException('Проблемный кейс не найден', 'problem_case_not_exists', 404);
        }

        $problemCase->setStatus($status_id);
        $problemCase->save();

        if ($problemCase->isStatusFinished()) {
            $message = SmsMessages::onNewProblemCases($problemCase->client_name . ' ' . $problemCase->client_surname, $problemCase->id);
            SendSmsJob::dispatch($problemCase->phone, $message);
        }

        SendHook::dispatch(new HookData(
            service: 'merchants',
            hookable_type: $problemCase->getTable(),
            hookable_id: $problemCase->id,
            created_from_str: 'PRM',
            created_by_id: $this->gatewayAuthUser->getId(),
            body: 'Обновлен на статус',
            keyword: ProblemCase::$statuses[$problemCase->status_id]['name'],
            action: 'update',
            class: 'info',
            action_at: null,
            created_by_str: $this->gatewayAuthUser->getName(),
        ));

        return $problemCase;
    }
}
