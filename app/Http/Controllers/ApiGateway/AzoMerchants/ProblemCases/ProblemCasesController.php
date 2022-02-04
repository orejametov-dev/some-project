<?php


namespace App\Http\Controllers\ApiGateway\AzoMerchants\ProblemCases;


use App\Http\Controllers\ApiGateway\ApiBaseController;
use App\Http\Requests\ApiPrm\Comments\StoreCommentRequest;
use App\Http\Requests\ApiPrm\ProblemCases\ProblemCaseAttachTagsRequest;
use App\Http\Requests\ApiPrm\ProblemCases\ProblemCaseSetAssignedRequest;
use App\Http\Requests\ApiPrm\ProblemCases\ProblemCaseSetStatusRequest;
use App\Http\Requests\ApiPrm\ProblemCases\ProblemCaseUpdateRequest;
use App\Modules\Merchants\DTO\Comments\CommentDTO;
use App\Modules\Merchants\Models\Comment;
use App\Modules\Merchants\Models\ProblemCase;
use App\UseCases\ProblemCase\AttachTagsProblemCaseUseCase;
use App\UseCases\ProblemCase\SetAssignedProblemCaseUseCase;
use App\UseCases\ProblemCase\SetStatusProblemCaseUseCase;
use App\UseCases\ProblemCase\StoreCommentProblemCaseUseCase;
use App\UseCases\ProblemCase\UpdateProblemCaseUseCase;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProblemCasesController extends ApiBaseController
{
    public function index(Request $request)
    {
        $problemCases = ProblemCase::with('tags')
            ->filterRequests($request)
            ->orderBy('created_at', 'DESC');

        if ($request->has('object') and $request->query('object') == true) {
            return $problemCases->first();
        }

        if ($request->has('paginate') and $request->query('paginate') == false) {
            return $problemCases->get();
        }
        return $problemCases->paginate($request->query('per_page') ?? 15);
    }

    public function show($id)
    {
        $problemCase = ProblemCase::with('tags')->findOrFail($id);

        return $problemCase;
    }

    public function update($id, ProblemCaseUpdateRequest $request, UpdateProblemCaseUseCase $updateProblemCaseUseCase)
    {
        return $updateProblemCaseUseCase->execute((int)$id, Carbon::parse($request->input('deadline')));
    }

    public function setManagerComment($id, StoreCommentRequest $request, StoreCommentProblemCaseUseCase $storeCommentProblemCaseUseCase)
    {
        $commentDTO = CommentDTO::fromArray($id, $request->validated(), Comment::PROBLEM_CASE_FOR_PRM);

        return $storeCommentProblemCaseUseCase->execute($commentDTO);
    }

    public function setMerchantComment($id, StoreCommentRequest $request, StoreCommentProblemCaseUseCase $storeCommentProblemCaseUseCase)
    {
        $commentDTO = CommentDTO::fromArray($id, $request->validated(), Comment::PROBLEM_CASE_FOR_MERCHANT);

        return $storeCommentProblemCaseUseCase->execute($commentDTO);
    }

    public function attachTags($id, ProblemCaseAttachTagsRequest $request, AttachTagsProblemCaseUseCase $attachTagsProblemCaseUseCase)
    {
        return $attachTagsProblemCaseUseCase->execute((int)$id, (array)$request->input('tags'));
    }

    public function setStatus($id, ProblemCaseSetStatusRequest $request, SetStatusProblemCaseUseCase $setStatusProblemCaseUseCase)
    {
        return $setStatusProblemCaseUseCase->execute((int)$id, (int)$request->input('status_id'));
    }

    public function setAssigned($id, ProblemCaseSetAssignedRequest $request, SetAssignedProblemCaseUseCase $setAssignedProblemCaseUseCase)
    {
        return $setAssignedProblemCaseUseCase->execute((int)$id, (int)$request->input('assigned_to_id'), (string)$request->input('assigned_to_name'));
    }

    public function getProblemCasesOfMerchantUser($user_id, Request $request)
    {
        $problemCases = ProblemCase::query()->with('tags', function ($query) {
            $query->where('type_id', 2);
        })->where('created_by_id', $user_id)
            ->orderByDesc('id');

        return $problemCases->paginate($request->query('per_page') ?? 15);
    }

}
