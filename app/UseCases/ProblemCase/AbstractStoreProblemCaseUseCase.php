<?php

declare(strict_types=1);

namespace App\UseCases\ProblemCase;

use Alifuz\Utils\Gateway\Entities\Auth\GatewayAuthUser;
use Alifuz\Utils\Gateway\Entities\GatewayApplication;
use App\DTOs\ProblemCases\ProblemCaseDTO;
use App\HttpServices\Hooks\DTO\HookData;
use App\Jobs\SendHook;
use App\Jobs\SendSmsJob;
use App\Modules\Merchants\Models\ProblemCase;
use App\Modules\Merchants\Models\ProblemCaseTag;
use App\Services\SMS\SmsMessages;

abstract class AbstractStoreProblemCaseUseCase
{
    public function __construct(
        private GatewayApplication $gatewayApplication,
        private GatewayAuthUser $gatewayAuthUser
    ) {
    }

    public function execute(ProblemCaseDTO $problemCaseDTO): ?ProblemCase
    {
        $data = $this->getDataByIdentifier($problemCaseDTO->getIdentifier());
        $this->checkStatusToFinished($problemCaseDTO->getIdentifier());

        $problemCase = new ProblemCase();

        $problemCase->merchant_id = $data->merchant_id;
        $problemCase->store_id = $data->store_id;
        $problemCase->client_id = $data->client_id;

        $problemCase->client_name = $data->client_name;
        $problemCase->client_surname = $data->client_surname;
        $problemCase->client_patronymic = $data->client_patronymic;
        $problemCase->phone = $data->phone;

        $problemCase->application_items = $data->application_items;

        $problemCase->created_by_id = $this->gatewayAuthUser->getId();
        $problemCase->created_by_name = $this->gatewayAuthUser->getName();
        $problemCase->created_from_name = $this->gatewayApplication->getApplication()->getValue();
        $problemCase->post_or_pre_created_by_id = $data->post_or_pre_created_by_id;
        $problemCase->post_or_pre_created_by_name = $data->post_or_pre_created_by_name;
        $problemCase->description = $problemCaseDTO->description;

        $this->setIdentifierNumberAndDate($problemCase, $problemCaseDTO->getIdentifier(), $data);

        $problemCase->setStatusNew();
        $problemCase->save();

        if ($problemCaseDTO->tags !== null) {
            $tags = [];
            foreach ($problemCaseDTO->tags as $item) {
                $tag = ProblemCaseTag::query()->firstOrCreate(['body' => $item['name'], 'type_id' => ProblemCaseTag::BEFORE_TYPE]);
                $tags[] = $tag->id;
            }
            $problemCase->tags()->attach($tags);
        }

        SendHook::dispatch(new HookData(
            service: 'merchants',
            hookable_type: $problemCase->getTable(),
            hookable_id: $problemCase->id,
            created_from_str: $this->gatewayApplication->getApplication()->getValue(),
            created_by_id: $this->gatewayAuthUser->getId(),
            body: 'Создан проблемный кейс co статусом',
            keyword: ProblemCase::$statuses[$problemCase->status_id]['name'],
            action: 'create',
            class: 'info',
            action_at: null,
            created_by_str: $this->gatewayAuthUser->getName(),
        ));

        $message = SmsMessages::onNewProblemCases($problemCase->client_name . ' ' . $problemCase->client_surname, $problemCase->id);
        SendSmsJob::dispatch($problemCase->phone, $message);

        return $problemCase;
    }

    abstract protected function checkStatusToFinished(string|int $identifier): void;

    abstract protected function getDataByIdentifier(string|int $identifier): mixed;

    abstract protected function setIdentifierNumberAndDate(ProblemCase $problemCase, string|int $identifier_number, mixed $data): void;
}
