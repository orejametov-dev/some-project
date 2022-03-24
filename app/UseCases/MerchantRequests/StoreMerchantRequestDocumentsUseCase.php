<?php

declare(strict_types=1);

namespace App\UseCases\MerchantRequests;

use App\DTOs\MerchantRequest\StoreMerchantRequestDocumentsDTO;
use App\Models\MerchantRequest;

class StoreMerchantRequestDocumentsUseCase
{
    public function __construct(
        private FindMerchantRequestByIdUseCase $findMerchantRequestByIdUseCase
    ) {
    }

    public function execute(int $id, StoreMerchantRequestDocumentsDTO $merchantRequestDocumentsDTO): MerchantRequest
    {
        $merchant_request = $this->findMerchantRequestByIdUseCase->execute($id);
        $merchant_request->director_name = $merchantRequestDocumentsDTO->getDirectorName();
        $merchant_request->phone = $merchantRequestDocumentsDTO->getPhone();
        $merchant_request->vat_number = $merchantRequestDocumentsDTO->getVatNumber();
        $merchant_request->mfo = $merchantRequestDocumentsDTO->getMfo();
        $merchant_request->tin = $merchantRequestDocumentsDTO->getTin();
        $merchant_request->oked = $merchantRequestDocumentsDTO->getOked();
        $merchant_request->bank_account = $merchantRequestDocumentsDTO->getBankAccount();
        $merchant_request->bank_name = $merchantRequestDocumentsDTO->getBankName();
        $merchant_request->address = $merchantRequestDocumentsDTO->getAddress();
        $merchant_request->documents_completed = true;
        $merchant_request->save();

        return $merchant_request;
    }
}
