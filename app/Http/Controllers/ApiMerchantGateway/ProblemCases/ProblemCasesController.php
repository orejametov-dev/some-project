<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiMerchantGateway\ProblemCases;

use Alifuz\Utils\Gateway\Entities\Auth\GatewayAuthUser;
use App\DTOs\Auth\AzoAccessDto;
use App\Enums\ProblemCaseStatusEnum;
use App\Exceptions\BusinessException;
use App\Filters\CommonFilters\StatusIdFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiMerchantGateway\ProblemCases\ProblemCaseResource;
use App\HttpRepositories\Hooks\DTO\HookData;
use App\Jobs\SendHook;
use App\Jobs\SendSmsJob;
use App\Mappings\ProblemCaseStatusMapping;
use App\Models\ProblemCase;
use App\Services\SMS\SmsMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProblemCasesController extends Controller
{
    public function index(Request $request, AzoAccessDto $azoAccessDto)
    {
        $problemCases = ProblemCase::query()->with('before_tags')
            ->byMerchant($azoAccessDto->merchant_id)
            ->filterRequest($request, [
                StatusIdFilter::class,
            ]);

        if ($request->query('object') == true) {
            $problemCases->first();
        }

        return ProblemCaseResource::collection($problemCases->paginate($request->query('per_page') ?? 15));
    }

    public function show($id)
    {
        $problemCase = ProblemCase::with('before_tags')
            ->find($id);

        if ($problemCase === null) {
            throw new BusinessException('Проблемный кейс не найден', 'object_not_found', 404);
        }

        return new ProblemCaseResource($problemCase);
    }

    public function setCommentFromMerchant($id, Request $request)
    {
        $this->validate($request, [
            'body' => 'string|required',
        ]);

        $problemCase = ProblemCase::query()->findOrFail($id);
        $problemCase->comment_from_merchant = $request->input('body');
        $problemCase->save();

        return new ProblemCaseResource($problemCase);
    }

    public function setStatus($id, Request $request, GatewayAuthUser $gatewayAuthUser, ProblemCaseStatusMapping $problemCaseStatusMapping)
    {
        $this->validate($request, [
            'status_id' => 'required|integer',
        ]);

        $problemCase = ProblemCase::query()->findOrFail($id);
        $problemCase->setStatus($request->input('status_id'));
        $problemCase->save();

        if ($problemCase->status_id === ProblemCaseStatusEnum::FINISHED()->getValue()) {
            preg_match('/' . preg_quote('9989') . '(.*)/', $problemCase->search_index, $phone);

            if (!empty($phone)) {
                $message = SmsMessages::onFinishedProblemCases($problemCase->client_name . ' ' . $problemCase->client_surname, $problemCase->id);
                SendSmsJob::dispatch($problemCase->phone, $message);
            }
        }

        SendHook::dispatch(new HookData(
            service: 'merchants',
            hookable_type: $problemCase->getTable(),
            hookable_id: $problemCase->id,
            created_from_str: 'MERCHANT',
            created_by_id: $gatewayAuthUser->getId(),
            body: 'Обновлен на статус',
            keyword: $problemCaseStatusMapping->getMappedValue(ProblemCaseStatusEnum::from($problemCase->status_id))['name'],
            action: 'create',
            class: 'info',
            action_at: null,
            created_by_str: $gatewayAuthUser->getName(),
        ));

        return new ProblemCaseResource($problemCase);
    }

    public function setEngage($id, GatewayAuthUser $gatewayAuthUser)
    {
        $problemCase = ProblemCase::query()->findOrFail($id);

        $problemCase->engaged_by_id = $gatewayAuthUser->getId();
        $problemCase->engaged_by_name = $gatewayAuthUser->getName();
        $problemCase->engaged_at = now();

        $problemCase->save();

        return new ProblemCaseResource($problemCase);
    }

    public function getStatuses(ProblemCaseStatusMapping $problemCaseStatusMapping)
    {
        return $problemCaseStatusMapping->getMappings();
    }

    public function getNewProblemCasesCounter(GatewayAuthUser $gatewayAuthUser, AzoAccessDto $azoAccessDto)
    {
        $counter = Cache::remember('new-problem-cases-counter_' . $azoAccessDto->merchant_id, 10 * 60, function () use ($azoAccessDto) {
            return ProblemCase::query()
                ->whereNull('engaged_by_id')
                ->whereNull('engaged_by_name')
                ->byMerchant($azoAccessDto->merchant_id)
                ->onlyNew()
                ->count();
        });

        return response()->json(['count' => (int) $counter]);
    }
}
