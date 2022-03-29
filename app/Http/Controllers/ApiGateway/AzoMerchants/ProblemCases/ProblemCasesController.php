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

    public function show($id, FindProblemCaseByIdUseCase $findProblemCaseByIdUseCase)
    {
        $problemCase = $findProblemCaseByIdUseCase->execute((int) $id);
        $problemCase->load(['tags', 'merchant', 'store']);

        return $problemCase;
    }

    public function update($id, UpdateProblemCaseRequest $request, UpdateProblemCaseUseCase $updateProblemCaseUseCase)
    {
        return $updateProblemCaseUseCase->execute((int) $id, Carbon::parse($request->input('deadline')));
    }

    public function setManagerComment($id, StoreCommentRequest $request, StoreCommentProblemCaseUseCase $storeCommentProblemCaseUseCase)
    {
        return $storeCommentProblemCaseUseCase->execute((int) $id, $request->input('body'), Comment::PROBLEM_CASE_FOR_PRM);
    }

    public function setMerchantComment($id, StoreCommentRequest $request, StoreCommentProblemCaseUseCase $storeCommentProblemCaseUseCase)
    {
        return $storeCommentProblemCaseUseCase->execute((int) $id, $request->input('body'), Comment::PROBLEM_CASE_FOR_MERCHANT);
    }

    public function attachTags($id, AttachProblemCaseTagsRequest $request, AttachTagsProblemCaseUseCase $attachTagsProblemCaseUseCase)
    {
        return $attachTagsProblemCaseUseCase->execute((int) $id, (array) $request->input('tags'));
    }

    public function setStatus($id, SetProblemCaseStatusRequest $request, SetStatusProblemCaseUseCase $setStatusProblemCaseUseCase)
    {
        return $setStatusProblemCaseUseCase->execute((int) $id, (int) $request->input('status_id'));
    }

    public function setAssigned($id, SetProblemCaseAssignedRequest $request, SetAssignedProblemCaseUseCase $setAssignedProblemCaseUseCase)
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
