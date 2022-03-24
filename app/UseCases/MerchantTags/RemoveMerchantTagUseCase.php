<?php

declare(strict_types=1);

namespace App\UseCases\MerchantTags;

use App\Exceptions\BusinessException;

class RemoveMerchantTagUseCase
{
    public function __construct(
        private FindMerchantTagByIdUseCase $findMerchantTagByIdUseCase
    ) {
    }

    public function execute(int $id): void
    {
        $tag = $this->findMerchantTagByIdUseCase->execute($id);

        if ($tag->merchants()->count()) {
            throw new BusinessException('Тег невозможно удалить');
        }

        $tag->delete();
    }
}
