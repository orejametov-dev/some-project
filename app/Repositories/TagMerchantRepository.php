<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class TagMerchantRepository
{
    private Tag|Builder $tag;

    public function __construct()
    {
        $this->tag = Tag::query();
    }

    /**
     * @param array $tag_ids
     * @return Collection
     */
    public function getByIds(array $tag_ids): Collection
    {
        return $this->tag->whereIn('id', $tag_ids)->get();
    }
}
