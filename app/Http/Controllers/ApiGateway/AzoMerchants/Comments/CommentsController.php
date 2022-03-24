<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiGateway\AzoMerchants\Comments;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiPrm\Comments\IndexComments;
use App\Http\Resources\ApiGateway\Comments\IndexCommentResource;
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

        return IndexCommentResource::collection($comments->paginate($request->query('per_page') ?? 15));
    }
}
