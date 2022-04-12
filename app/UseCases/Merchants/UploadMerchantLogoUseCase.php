<?php

declare(strict_types=1);

namespace App\UseCases\Merchants;

use App\HttpRepositories\Storage\StorageHttpRepository;
use App\Models\Merchant;
use App\Repositories\MerchantRepository;
use Illuminate\Http\UploadedFile;

class UploadMerchantLogoUseCase
{
    public function __construct(
        private MerchantRepository $merchantRepository,
        private FindMerchantByIdUseCase $findMerchantByIdUseCase,
        private StorageHttpRepository $storageHttpRepository
    ) {
    }

    public function execute(int $id, UploadedFile $uploaded_file): Merchant
    {
        $merchant = $this->findMerchantByIdUseCase->execute($id);

        if ($merchant->logo_url) {
            (new StorageHttpRepository())->destroy($merchant->logo_url);
        }
        $storage_file = $this->storageHttpRepository->uploadFile($uploaded_file, 'merchants');

        $merchant->logo_url = $storage_file->getUrl();
        $this->merchantRepository->save($merchant);

        return $merchant;
    }
}
