<?php

namespace App\UseCases\ApiComplianceGateway\ProblemCases;

use App\Exceptions\ApiBusinessException;
use App\HttpRepositories\CoreHttpRepositories\CoreHttpRepository;
use App\HttpServices\Hooks\DTO\HookData;
use App\Jobs\SendHook;
use App\Jobs\SendSmsJob;
use App\Modules\Merchants\DTO\ProblemCases\ProblemCaseDTO;
use App\Modules\Merchants\Models\ProblemCase;
use App\Services\SMS\SmsMessages;

class StoreProblemCasesUseCase
{
    public function __construct(
        private CoreHttpRepository $coreHttpRepository
    )
    {
    }

    public function execute(ProblemCaseDTO $problemCaseDTO, $user): ?ProblemCase
    {
        $data = $this->coreHttpRepository->getApplicationDataByApplicationId($problemCaseDTO->application_id);

        if (ProblemCase::query()->where('credit_number', $problemCaseDTO->application_id)
            ->where('status_id', '!=', ProblemCase::FINISHED)
            ->orderByDesc('id')->exists()) {
            throw new ApiBusinessException('На данный кредитный номер был уже создан проблемный кейс', 'problem_case_exist', [
                'ru' => "На данный кредитный номер был уже создан проблемный кейс",
                'uz' => 'Bu kredit raqamiga tegishli muammoli keys avval yuborilgan.'
            ], 400);
        }

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

        $problemCase->created_by_id = $user->id;
        $problemCase->created_by_name = $user->name;
        $problemCase->created_from_name = $problemCaseDTO->created_from_name;

        $problemCase->post_or_pre_created_by_id = $data->post_or_pre_created_by_id;
        $problemCase->post_or_pre_created_by_name = $data->post_or_pre_created_by_name;
        $problemCase->description = $problemCaseDTO->description;

        $problemCase->application_id = $problemCaseDTO->application_id;
        $problemCase->application_created_at = $data->application_created_at;

        $problemCase->setStatusNew();
        $problemCase->save();

        SendHook::dispatch(new HookData(
            service: 'merchants',
            hookable_type: $problemCase->getTable(),
            hookable_id: $problemCase->id,
            created_from_str: 'CALLS',
            created_by_id: $user->id,
            body: 'Создан проблемный кейс co статусом',
            keyword: ProblemCase::$statuses[$problemCase->status_id]['name'],
            action: 'create',
            class: 'info',
            action_at: null,
            created_by_str: $user->name,
        ));

        $message = SmsMessages::onNewProblemCases($problemCase->client_name . ' ' . $problemCase->client_surname, $problemCase->id);
        SendSmsJob::dispatch($problemCase->phone, $message);

        return $problemCase;
    }
}
