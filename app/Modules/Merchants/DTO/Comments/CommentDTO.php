<?php

declare(strict_types=1);

namespace App\Modules\Merchants\DTO\Comments;

use Alifuz\Utils\Parser\ParseDataTrait;

class CommentDTO
{
    use ParseDataTrait;

    public function __construct(
        public int $commentable_id,
        public string $commentable_type,
        public string $body
    ) {
    }

    public static function fromArray(int $id, array $data, string $commentable_type)
    {
        return new self(
            self::parseInt($id),
            self::parseString($commentable_type),
            self::parseString($data['body'])
        );
    }
}
