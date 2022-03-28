<?php

declare(strict_types=1);

namespace App\UseCases\MerchantInfos;

use App\Services\WordService;

class GetMerchantInfoContractUseCase
{
    public function __construct(
        private FindMerchantInfoByIdUseCase $findMerchantInfoByIdUseCase,
        private WordService $wordService
    ) {
    }

    public function execute(int $id): string
    {
        $merchant_info = $this->findMerchantInfoByIdUseCase->execute($id);

        $contract_path = 'app/prm_merchant_contract.docx';

        return $this->wordService->createContract($merchant_info, $contract_path);
    }
}
