<?php

declare(strict_types=1);

namespace App\UseCases\ProblemCase;

use Alifuz\Utils\Gateway\Entities\Auth\GatewayAuthUser;
use App\Models\Comment;

class StoreCommentProblemCaseUseCase
{
    public function __construct(
        private GatewayAuthUser $gatewayAuthUser
    ) {
    }

    public function execute(int $id, string $body, string $type): Comment
    {
        $comment = new Comment();
        $comment->commentable_type = $type;
        $comment->commentable_id = $id;
        $comment->body = $body;
        $comment->created_by_id = $this->gatewayAuthUser->getId();
        $comment->created_by_name = $this->gatewayAuthUser->getName();
        $comment->save();

        return $comment;
    }
}
