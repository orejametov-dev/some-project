<?php

namespace Tests\Unit\Merchants;

use App\HttpRepositories\HttpResponses\Storage\UploadFileResponse;
use App\HttpRepositories\Storage\StorageHttpRepository;
use App\Models\Merchant;
use App\Repositories\MerchantRepository;
use App\UseCases\Merchants\FindMerchantByIdUseCase;
use App\UseCases\Merchants\UploadMerchantLogoUseCase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class UploadMerchantLogoUseCaseTest extends TestCase
{
    private FindMerchantByIdUseCase $findMerchantByIdUseCase;
    private StorageHttpRepository $storageHttpRepository;
    private UploadMerchantLogoUseCase $uploadMerchantLogoUseCase;

    public function setUp(): void
    {
        $merchantRepository = $this->createMock(MerchantRepository::class);
        $this->findMerchantByIdUseCase = $this->createMock(FindMerchantByIdUseCase::class);
        $this->storageHttpRepository = $this->createMock(StorageHttpRepository::class);
        $this->uploadMerchantLogoUseCase = new UploadMerchantLogoUseCase(
            merchantRepository: $merchantRepository,
            findMerchantByIdUseCase: $this->findMerchantByIdUseCase,
            storageHttpRepository: $this->storageHttpRepository,
        );

        parent::setUp();
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
        $merchant->logo_url = null;

        $file = UploadFileResponse::fromArray([
            'id' => 1,
            'uuid' => 'test',
            'key' => 'test',
            'mime_type' => 'jpg',
            'size' => 1,
            'storage_path' => '/test',
            'url' => '/test/test',
        ]);

        $image = UploadedFile::fake()->image('test.jpg');

        $this->findMerchantByIdUseCase->method('execute')->willReturn($merchant);
        $this->storageHttpRepository->method('uploadFile')->willReturn($file);

        $response = $this->uploadMerchantLogoUseCase->execute($merchant->id, $image);

        static::assertEquals($file->getUrl(), $response->logo_url);
    }
}
