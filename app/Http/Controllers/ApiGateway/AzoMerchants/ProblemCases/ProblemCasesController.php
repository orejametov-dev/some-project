<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiGateway\AzoMerchants\ProblemCases;

use App\Filters\CommonFilters\DateFilter;
use App\Filters\CommonFilters\StatusIdFilter;
use App\Filters\Merchant\MerchantIdFilter;
use App\Filters\Merchant\MerchantIdsFilter;
use App\Filters\ProblemCase\AssignedToIdFilter;
use App\Filters\ProblemCase\CreatedFromNameFilter;
use App\Filters\ProblemCase\ProblemCaseTagIdFilter;
use App\Filters\ProblemCase\QProblemCaseFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiPrm\Comments\StoreCommentRequest;
use App\Http\Requests\ApiPrm\ProblemCases\AttachProblemCaseTagsRequest;
use App\Http\Requests\ApiPrm\ProblemCases\SetProblemCaseAssignedRequest;
use App\Http\Requests\ApiPrm\ProblemCases\SetProblemCaseStatusRequest;
use App\Http\Requests\ApiPrm\ProblemCases\UpdateProblemCaseRequest;
use App\Models\Comment;
use App\Models\ProblemCase;
use App\UseCases\ProblemCase\AttachTagsProblemCaseUseCase;
use App\UseCases\ProblemCase\FindProblemCaseByIdUseCase;
use App\UseCases\ProblemCase\SetAssignedProblemCaseUseCase;
use App\UseCases\ProblemCase\SetStatusProblemCaseUseCase;
use App\UseCases\ProblemCase\StoreCommentProblemCaseUseCase;
use App\UseCases\ProblemCase\UpdateProblemCaseUseCase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProblemCasesController extends Controller
{
    public function index(Request $request): JsonResource
    {
        $problemCases = ProblemCase::query()
            ->with(['tags', 'merchant', 'store'])
            ->filterRequest($request, [
                QProblemCaseFilter::class,
                StatusIdFilter::class,
                MerchantIdsFilter::class,
                AssignedToIdFilter::class,
                DateFilter::class,
                ProblemCaseTagIdFilter::class,
                CreatedFromNameFilter::class,
                MerchantIdFilter::class,
                ])->orderBy('created_at', 'DESC');

        return JsonResource::collection($problemCases->paginate($request->query('per_page') ?? 15));
    }

    public function show(int $id, FindProblemCaseByIdUseCase $findProblemCaseByIdUseCase): JsonResource
    {
        $problemCase = $findProblemCaseByIdUseCase->execute($id);
        $problemCase->load(['tags', 'merchant', 'store']);

        return new JsonResource($problemCase);
    }

    public function update(int $id, UpdateProblemCaseRequest $request, UpdateProblemCaseUseCase $updateProblemCaseUseCase): JsonResource
    {
        $problemCase = $updateProblemCaseUseCase->execute($id, Carbon::parse($request->input('deadline')));

        return new JsonResource($problemCase);
    }

    public function setManagerComment(int $id, StoreCommentRequest $request, StoreCommentProblemCaseUseCase $storeCommentProblemCaseUseCase): JsonResource
    {
        $comment = $storeCommentProblemCaseUseCase->execute($id, $request->input('body'), Comment::PROBLEM_CASE_FOR_PRM);

        return new JsonResource($comment);
    }

    public function setMerchantComment(int $id, StoreCommentRequest $request, StoreCommentProblemCaseUseCase $storeCommentProblemCaseUseCase): JsonResource
    {
        $comment = $storeCommentProblemCaseUseCase->execute($id, $request->input('body'), Comment::PROBLEM_CASE_FOR_MERCHANT);

        return new JsonResource($comment);
    }

    public function attachTags(int $id, AttachProblemCaseTagsRequest $request, AttachTagsProblemCaseUseCase $attachTagsProblemCaseUseCase): JsonResource
    {
        $problemCase = $attachTagsProblemCaseUseCase->execute($id, (array) $request->input('tags'));

        return JsonResource::collection($problemCase);
    }

    public function setStatus(int $id, SetProblemCaseStatusRequest $request, SetStatusProblemCaseUseCase $setStatusProblemCaseUseCase): JsonResource
    {
        $problemCase = $setStatusProblemCaseUseCase->execute($id, (int) $request->input('status_id'));

        return new JsonResource($problemCase);
    }

    public function setAssigned(int $id, SetProblemCaseAssignedRequest $request, SetAssignedProblemCaseUseCase $setAssignedProblemCaseUseCase): JsonResource
    {
        $problemCase = $setAssignedProblemCaseUseCase->execute($id, (int) $request->input('assigned_to_id'), (string) $request->input('assigned_to_name'));

        return new JsonResource($problemCase);
    }

    public function getProblemCasesOfMerchantUser(int $user_id, Request $request): JsonResource
    {
        $problemCases = ProblemCase::query()
            ->with(['tags'])
            ->where('post_or_pre_created_by_id', $user_id)
            ->orderByDesc('id');

        return JsonResource::collection($problemCases->paginate($request->query('per_page') ?? 15));
    }
}
