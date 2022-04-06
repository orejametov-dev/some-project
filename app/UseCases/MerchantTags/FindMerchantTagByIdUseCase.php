<?php

declare(strict_types=1);

namespace App\UseCases\MerchantTags;

use App\Exceptions\NotFoundException;
use App\Models\Tag;

class FindMerchantTagByIdUseCase
{
    public function execute(int $id): Tag
    {
        $tag = Tag::query()->find($id);
        if ($tag === null) {
            throw new NotFoundException('Тег не найден');
        }

        return $tag;
    }
}
