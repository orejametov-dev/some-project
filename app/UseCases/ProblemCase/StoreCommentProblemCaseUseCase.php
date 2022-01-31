<?php

namespace App\UseCases\ProblemCase;

use Alifuz\Utils\Gateway\Entities\Auth\GatewayAuthUser;
use App\Modules\Merchants\DTO\Comments\CommentDTO;
use App\Modules\Merchants\Models\Comment;

class StoreCommentProblemCaseUseCase
{
    public function __construct(
        private GatewayAuthUser $gatewayAuthUser
    )
    {
    }

    public function execute(CommentDTO $commentDTO): Comment
    {
        $comment = new Comment();
        $comment->commentable_type = $commentDTO->commentable_type;
        $comment->commentable_id = $commentDTO->commentable_id;
        $comment->body = $commentDTO->body;
        $comment->created_by_id = $this->gatewayAuthUser->getId();
        $comment->created_by_name = $this->gatewayAuthUser->getName();
        $comment->save();

        return $comment;
    }
}
