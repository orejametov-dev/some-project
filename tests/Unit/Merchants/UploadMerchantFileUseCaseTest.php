<?php

namespace Tests\Unit\Merchants;

use App\HttpRepositories\HttpResponses\Storage\UploadFileResponse;
use App\HttpRepositories\Storage\StorageHttpRepository;
use App\Models\Merchant;
use App\Repositories\FileRepository;
use App\UseCases\Merchants\FindMerchantByIdUseCase;
use App\UseCases\Merchants\UploadMerchantFileUseCase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class UploadMerchantFileUseCaseTest extends TestCase
{
    private FindMerchantByIdUseCase $findMerchantByIdUseCase;
    private StorageHttpRepository $storageHttpRepository;
    private UploadMerchantFileUseCase $uploadMerchantFileUseCase;

    public function setUp(): void
    {
        $this->findMerchantByIdUseCase = $this->createMock(FindMerchantByIdUseCase::class);
        $this->storageHttpRepository = $this->createMock(StorageHttpRepository::class);
        $fileRepository = $this->createMock(FileRepository::class);
        $this->uploadMerchantFileUseCase = new UploadMerchantFileUseCase(
            findMerchantByIdUseCase: $this->findMerchantByIdUseCase,
            storageHttpRepository: $this->storageHttpRepository,
            fileRepository: $fileRepository,
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

        $response = $this->uploadMerchantFileUseCase->execute($merchant->id, 'passport', $image);

        static::assertEquals($file->getUrl(), $response->url);
    }
}
