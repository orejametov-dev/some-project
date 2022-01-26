<?php

namespace App\UseCases\ApplicationConditions;

use App\Exceptions\BusinessException;
use App\HttpRepositories\Core\CoreHttpRepository;
use App\HttpServices\Hooks\DTO\HookData;
use App\Jobs\SendHook;
use App\Modules\Merchants\Models\Condition;
use Illuminate\Support\Facades\Cache;

class DeleteApplicationConditionUseCase
{

    public function __construct(
        private CoreHttpRepository $coreHttpRepository
    )
    {
    }

    public function execute(int $condition_id ,$user)
    {
        $condition = Condition::query()->find($condition_id);

        if ($condition === null)
        {
            throw new BusinessException('Условие не найдено' , 'condition_not_found' , 404);
        }

        $applications = $this->coreHttpRepository->getApplicationConditionId($condition_id);

        if ($applications) {
            return response()->json(['message' => 'Условие не может быть удалено'], 400);
        }

        $merchant = $condition->merchant;

        $condition->delete();

        SendHook::dispatch(new HookData(
            service: 'merchants',
            hookable_type: $merchant->getTable(),
            hookable_id: $merchant->id,
            created_from_str: 'PRM',
            created_by_id: $user->id,
            body: 'Условие удалено',
            keyword: 'id: ' . $condition->id . ' ' . $condition->title,
            action: 'delete',
            class: 'danger',
            action_at: null,
            created_by_str: $user->name,
        ));

        Cache::tags($merchant->id)->flush();

        return response()->json(['message' => 'Условие удалено']);
    }
}
