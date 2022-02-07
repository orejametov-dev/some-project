<?php

namespace App\Http\Controllers\ApiGateway\AzoMerchants\Comments;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiPrm\Comments\IndexComments;
use App\Modules\Merchants\Models\Comment;

class CommentsController extends Controller
{
    public function index(IndexComments $request)
    {
        $comments = Comment::query()
            ->where('commentable_type', $request->input('commentable_type'))
            ->where('commentable_id', $request->input('commentable_id'))
            ->orderRequest($request);

        return $comments->paginate($request->query('per_page') ?? 15);
    }
}
