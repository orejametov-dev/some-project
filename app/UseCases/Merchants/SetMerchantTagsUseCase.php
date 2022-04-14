<?php

declare(strict_types=1);

namespace App\UseCases\Merchants;

use App\Exceptions\BusinessException;
use App\Models\Merchant;
use App\Models\Tag;
use App\Repositories\MerchantRepository;
use App\Repositories\TagMerchantRepository;

class SetMerchantTagsUseCase
{
    public function __construct(
        private FindMerchantByIdUseCase $findMerchantByIdUseCase,
        private TagMerchantRepository $tagMerchantRepository,
        private MerchantRepository $merchantRepository,
    ) {
    }

    public function execute(int $id, array $tag_ids): Merchant
    {
        $merchant = $this->findMerchantByIdUseCase->execute($id);

        $tags = $this->tagMerchantRepository->getByIds($tag_ids);

        // TODO validate tag_ids exist
//
//        foreach ($tag_ids as $tag) {
//            if (!$tags->contains('id', $tag)) {
//                throw new BusinessException('Указан не правильный тег');
//            }
//        }
        $this->merchantRepository->syncTags($merchant, $tags);

        return $merchant;
    }
}
