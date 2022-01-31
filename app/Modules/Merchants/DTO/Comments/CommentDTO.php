<?php

namespace App\Modules\Merchants\DTO\Comments;

class CommentDTO
{
    public function __construct(
        public string $commentable_type,
        public int    $commentable_id,
        public string $body,
        public int $created_by_id,
        public string $created_by_name,

    )
    {
    }
}
