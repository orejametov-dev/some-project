<?php

namespace App\UseCases\ProblemCase;

use App\Modules\Merchants\DTO\Comments\CommentDTO;
use App\Modules\Merchants\Models\Comment;

class StoreCommentProblemCaseUseCase
{
    public function execute(CommentDTO $commentDTO): Comment
    {
        $comment = new Comment();
        $comment->commentable_type = $commentDTO->commentable_type;
        $comment->commentable_id = $commentDTO->commentable_id;
        $comment->body = $commentDTO->body;
        $comment->created_by_id = $commentDTO->created_by_id;
        $comment->created_by_name = $commentDTO->created_by_name;
        $comment->save();

        return $comment;
    }
}