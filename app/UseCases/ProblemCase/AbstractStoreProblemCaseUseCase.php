<?php

declare(strict_types=1);

namespace App\UseCases\ProblemCase;

use App\HttpServices\Hooks\DTO\HookData;
use App\Jobs\SendHook;
use App\Jobs\SendSmsJob;
use App\Modules\Merchants\DTO\ProblemCases\ProblemCaseDTO;
use App\Modules\Merchants\Models\ProblemCase;
use App\Services\SMS\SmsMessages;

abstract class AbstractStoreProblemCaseUseCase
{
    public function execute(ProblemCaseDTO $problemCaseDTO): ?ProblemCase
    {
        $data = $this->getDataByIdentifier($problemCaseDTO->identifier);
        $this->checkStatusToFinished($problemCaseDTO->identifier);

        $problemCase = new ProblemCase();

        $problemCase->merchant_id = $data->merchant_id;
        $problemCase->store_id = $data->store_id;
        $problemCase->client_id = $data->client_id;

        $problemCase->search_index = $data->client_name
            . ' ' . $data->client_surname
            . ' ' . $data->client_patronymic
            . ' ' . $data->phone;

        $problemCase->client_name = $data->client_name;
        $problemCase->client_surname = $data->client_surname;
        $problemCase->client_patronymic = $data->client_patronymic;
        $problemCase->phone = $data->phone;

        $problemCase->application_items = $data->application_items;

        $problemCase->created_by_id = $problemCaseDTO->user_id;
        $problemCase->created_by_name = $problemCaseDTO->user_name;
        $problemCase->created_from_name = $problemCaseDTO->created_from_name;

        $problemCase->post_or_pre_created_by_id = $data->post_or_pre_created_by_id;
        $problemCase->post_or_pre_created_by_name = $data->post_or_pre_created_by_name;
        $problemCase->description = $problemCaseDTO->description;

        $this->setIdentifierNumberAndDate($problemCase , $problemCaseDTO->identifier , $data);

        $problemCase->setStatusNew();
        $problemCase->save();

        SendHook::dispatch(new HookData(
            service: 'merchants',
            hookable_type: $problemCase->getTable(),
            hookable_id: $problemCase->id,
            created_from_str: $problemCaseDTO->created_from_name,
            created_by_id: $problemCaseDTO->user_id,
            body: 'Создан проблемный кейс co статусом',
            keyword: ProblemCase::$statuses[$problemCase->status_id]['name'],
            action: 'create',
            class: 'info',
            action_at: null,
            created_by_str: $problemCaseDTO->user_name,
        ));

        $message = SmsMessages::onNewProblemCases($problemCase->client_name . ' ' . $problemCase->client_surname, $problemCase->id);
        SendSmsJob::dispatch($problemCase->phone, $message);

        return $problemCase;
    }

    abstract protected function checkStatusToFinished(string|int $identifier): void;

    abstract protected function getDataByIdentifier(string|int $identifier): mixed;

    abstract protected function setIdentifierNumberAndDate(ProblemCase $problemCase , $identifier_number , $data);
}
