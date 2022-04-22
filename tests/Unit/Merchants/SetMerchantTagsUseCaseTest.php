<?php

namespace Tests\Unit\Merchants;

use App\Models\Merchant;
use App\Models\Tag;
use App\Repositories\MerchantRepository;
use App\Repositories\TagMerchantRepository;
use App\UseCases\Merchants\FindMerchantByIdUseCase;
use App\UseCases\Merchants\SetMerchantTagsUseCase;
use Illuminate\Database\Eloquent\Collection;
use PHPUnit\Framework\TestCase;

class SetMerchantTagsUseCaseTest extends TestCase
{
    private TagMerchantRepository $tagMerchantRepository;
    private FindMerchantByIdUseCase $findMerchantByIdUseCase;
    private SetMerchantTagsUseCase $setMerchantTagsUseCase;

    protected function setUp(): void
    {
        $merchantRepository = $this->createMock(MerchantRepository::class);
        $this->tagMerchantRepository = $this->createMock(TagMerchantRepository::class);
        $this->findMerchantByIdUseCase = $this->createMock(FindMerchantByIdUseCase::class);
        $this->setMerchantTagsUseCase = new SetMerchantTagsUseCase(
            findMerchantByIdUseCase: $this->findMerchantByIdUseCase,
            tagMerchantRepository: $this->tagMerchantRepository,
            merchantRepository: $merchantRepository
        );
    }

    public function testSuccess()
    {
        $merchant = new Merchant();
        $merchant->id = 1;
        $merchant->name = 'test';
        $merchant->legal_name = 'test';
        $merchant->legal_name_prefix = 'LLC';
        $merchant->token = 'test';
        $merchant->maintainer_id = 1;
        $merchant->company_id = 1;

        $tag = new Tag();
        $tag->id = 2;
        $tag->title = 'Бытовая техника';

        $tag2 = new Tag();
        $tag2->id = 1;
        $tag2->title = 'Компьютеры';

        $this->findMerchantByIdUseCase->method('execute')->willReturn($merchant);
        $this->tagMerchantRepository->method('getByIds')->willReturn(Collection::make([$tag, $tag2]));

        $response = $this->setMerchantTagsUseCase->execute($merchant->id, [1, 2]);

        static::assertIsObject($merchant, $response);
    }
}
