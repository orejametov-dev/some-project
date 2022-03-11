<?php

declare(strict_types=1);

namespace App\UseCases\ApplicationConditions;

use Alifuz\Utils\Gateway\Entities\Auth\GatewayAuthUser;
use App\Exceptions\BusinessException;
use App\HttpRepositories\Core\CoreHttpRepository;
use App\HttpServices\Hooks\DTO\HookData;
use App\Jobs\SendHook;
use App\UseCases\Cache\FlushCacheUseCase;

class DeleteApplicationConditionUseCase
{
    public function __construct(
        private CoreHttpRepository $coreHttpRepository,
        private FindConditionByIdUseCase $findConditionUseCase,
        private FlushCacheUseCase $flushCacheUseCase,
        private GatewayAuthUser $gatewayAuthUser
    ) {
    }

    public function execute(int $condition_id): void
    {
        $condition = $this->findConditionUseCase->execute($condition_id);
        $applications = $this->coreHttpRepository->checkApplicationToExistByConditionId($condition_id);

        if ($applications) {
            throw new BusinessException('Условие не может быть удалено', '', 400);
        }

        $merchant = $condition->merchant;
        $condition->delete();

        SendHook::dispatch(new HookData(
            service: 'merchants',
            hookable_type: $merchant->getTable(),
            hookable_id: $merchant->id,
            created_from_str: 'PRM',
            created_by_id: $this->gatewayAuthUser->getId(),
            body: 'Условие удалено',
            keyword: 'id: ' . $condition->id . ' ' . $condition->title,
            action: 'delete',
            class: 'danger',
            action_at: null,
            created_by_str: $this->gatewayAuthUser->getName(),
        ));

        $this->flushCacheUseCase->execute($merchant->id);
    }
}
