<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiGateway\AzoMerchants\ProblemCases;

use App\DTOs\Comments\CommentDTO;
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
use App\Http\Requests\ApiPrm\ProblemCases\ProblemCaseAttachTagsRequest;
use App\Http\Requests\ApiPrm\ProblemCases\ProblemCaseSetAssignedRequest;
use App\Http\Requests\ApiPrm\ProblemCases\ProblemCaseSetStatusRequest;
use App\Http\Requests\ApiPrm\ProblemCases\ProblemCaseUpdateRequest;
use App\Models\Comment;
use App\Models\ProblemCase;
use App\UseCases\ProblemCase\AttachTagsProblemCaseUseCase;
use App\UseCases\ProblemCase\SetAssignedProblemCaseUseCase;
use App\UseCases\ProblemCase\SetStatusProblemCaseUseCase;
use App\UseCases\ProblemCase\StoreCommentProblemCaseUseCase;
use App\UseCases\ProblemCase\UpdateProblemCaseUseCase;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProblemCasesController extends Controller
{
    public function index(Request $request)
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

        return $problemCases->paginate($request->query('per_page') ?? 15);
    }

    public function show($id)
    {
        $problemCase = ProblemCase::with(['tags', 'merchant', 'store'])->findOrFail($id);

        return $problemCase;
    }

    public function update($id, ProblemCaseUpdateRequest $request, UpdateProblemCaseUseCase $updateProblemCaseUseCase)
    {
        return $updateProblemCaseUseCase->execute((int) $id, Carbon::parse($request->input('deadline')));
    }

    public function setManagerComment($id, StoreCommentRequest $request, StoreCommentProblemCaseUseCase $storeCommentProblemCaseUseCase)
    {
        $commentDTO = CommentDTO::fromArray((int) $id, $request->validated(), Comment::PROBLEM_CASE_FOR_PRM);

        return $storeCommentProblemCaseUseCase->execute($commentDTO);
    }

    public function setMerchantComment($id, StoreCommentRequest $request, StoreCommentProblemCaseUseCase $storeCommentProblemCaseUseCase)
    {
        $commentDTO = CommentDTO::fromArray((int) $id, $request->validated(), Comment::PROBLEM_CASE_FOR_MERCHANT);

        return $storeCommentProblemCaseUseCase->execute($commentDTO);
    }

    public function attachTags($id, ProblemCaseAttachTagsRequest $request, AttachTagsProblemCaseUseCase $attachTagsProblemCaseUseCase)
    {
        return $attachTagsProblemCaseUseCase->execute((int) $id, (array) $request->input('tags'));
    }

    public function setStatus($id, ProblemCaseSetStatusRequest $request, SetStatusProblemCaseUseCase $setStatusProblemCaseUseCase)
    {
        return $setStatusProblemCaseUseCase->execute((int) $id, (int) $request->input('status_id'));
    }

    public function setAssigned($id, ProblemCaseSetAssignedRequest $request, SetAssignedProblemCaseUseCase $setAssignedProblemCaseUseCase)
    {
        return $setAssignedProblemCaseUseCase->execute((int) $id, (int) $request->input('assigned_to_id'), (string) $request->input('assigned_to_name'));
    }

    public function getProblemCasesOfMerchantUser($user_id, Request $request)
    {
        $problemCases = ProblemCase::query()
            ->with(['tags'])
            ->where('post_or_pre_created_by_id', $user_id)
            ->orderByDesc('id');

        return $problemCases->paginate($request->query('per_page') ?? 15);
    }
}
