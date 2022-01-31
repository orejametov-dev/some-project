<?php


namespace App\Http\Controllers\ApiGateway\AzoMerchants\ProblemCases;


use App\Http\Controllers\ApiGateway\ApiBaseController;
use App\Http\Requests\ApiPrm\Comments\StoreCommentRequest;
use App\Http\Requests\ApiPrm\Merchants\ProblemCases\ProblemCaseAttachTagsRequest;
use App\Http\Requests\ApiPrm\Merchants\ProblemCases\ProblemCaseSetAssignedRequest;
use App\Http\Requests\ApiPrm\Merchants\ProblemCases\ProblemCaseSetStatusRequest;
use App\Http\Requests\ApiPrm\Merchants\ProblemCases\ProblemCaseUpdateRequest;
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

    public function update($id, ProblemCaseUpdateRequest $request , UpdateProblemCaseUseCase $updateProblemCaseUseCase)
    {
        return $updateProblemCaseUseCase->execute((int) $id , Carbon::parse($request->input('deadline')));
    }

    public function setManagerComment($id, StoreCommentRequest $request, StoreCommentProblemCaseUseCase $storeCommentProblemCaseUseCase)
    {
        $commentDTO = new CommentDTO(
            commentable_type: Comment::PROBLEM_CASE_FOR_PRM,
            commentable_id: (int) $id,
            body: (string) $request->input('body'),
            created_by_id: (int) $this->user->id,
            created_by_name: (string) $this->user->name
        );

        return $storeCommentProblemCaseUseCase->execute($commentDTO);
    }

    public function setMerchantComment($id, StoreCommentRequest $request , StoreCommentProblemCaseUseCase $storeCommentProblemCaseUseCase)
    {
        $commentDTO = new CommentDTO(
            commentable_type: Comment::PROBLEM_CASE_FOR_MERCHANT,
            commentable_id: (int) $id,
            body: (string) $request->input('body'),
            created_by_id: (int) $this->user->id,
            created_by_name: (string) $this->user->name
        );

        return $storeCommentProblemCaseUseCase->execute($commentDTO);
    }

    public function attachTags($id, ProblemCaseAttachTagsRequest $request , AttachTagsProblemCaseUseCase $attachTagsProblemCaseUseCase)
    {
        return $attachTagsProblemCaseUseCase->execute( (int) $id , (array) $request->input('tags'));
    }

    public function setStatus($id , ProblemCaseSetStatusRequest $request  , SetStatusProblemCaseUseCase $setStatusProblemCaseUseCase)
    {
        return $setStatusProblemCaseUseCase->execute((int) $id , (int) $request->input('status_id'));
    }

    public function setAssigned($id, ProblemCaseSetAssignedRequest $request , SetAssignedProblemCaseUseCase $setAssignedProblemCaseUseCase)
    {
        return $setAssignedProblemCaseUseCase->execute((int) $id , (int) $request->input('assigned_to_id') , (string) $request->input('assigned_to_name'));
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
