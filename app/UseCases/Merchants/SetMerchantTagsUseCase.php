<?php

declare(strict_types=1);

namespace App\UseCases\Merchants;

use App\Exceptions\BusinessException;
use App\Models\Merchant;
use App\Models\Tag;

class SetMerchantTagsUseCase
{
    public function __construct(
        private FindMerchantByIdUseCase $findMerchantByIdUseCase
    ) {
    }

    public function execute(int $id, array $tag_ids): Merchant
    {
        $merchant = $this->findMerchantByIdUseCase->execute($id);

        $tags = Tag::query()->whereIn('id', $tag_ids)->get();
        // TODO validate tag_ids exist
//
//        foreach ($tag_ids as $tag) {
//            if (!$tags->contains('id', $tag)) {
//                throw new BusinessException('Указан не правильный тег');
//            }
//        }

        $merchant->tags()->sync($tags);

        return $merchant;
    }
}
