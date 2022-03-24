<?php

declare(strict_types=1);

namespace App\UseCases\MerchantTags;

use App\Exceptions\BusinessException;
use App\Models\Tag;

class FindMerchantTagByIdUseCase
{
    public function execute(int $id): Tag
    {
        $tag = Tag::query()->find($id);
        if ($tag === null) {
            throw new BusinessException('Тег не найден', 'object_not_found', 404);
        }

        return $tag;
    }
}
