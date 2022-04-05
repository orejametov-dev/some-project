<?php

namespace App\Http\Controllers\ApiCallsGateway\Comments;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiPrm\Comments\IndexComments;
use App\Http\Resources\ApiCallsGateway\Comments\CommentResource;
use App\Models\Comment;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentsController extends Controller
{
    public function index(IndexComments $request): JsonResource
    {
        $comments = Comment::query()
            ->where('commentable_type', $request->input('commentable_type'))
            ->where('commentable_id', $request->input('commentable_id'))
            ->orderRequest($request);

        return CommentResource::collection($comments->paginate($request->query('per_page') ?? 15));
    }
}
