<?php

declare(strict_types=1);

namespace App\UseCases\MerchantTags;

use App\Models\Tag;

class StoreMerchantTagUseCase
{
    public function execute(string $title): Tag
    {
        $merchant_tag = new Tag();
        $merchant_tag->title = $title;
        $merchant_tag->save();

        return $merchant_tag;
    }
}
