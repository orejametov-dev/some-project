<?php

declare(strict_types=1);

namespace App\UseCases\MerchantRequests;

use App\DTOs\MerchantRequest\UpdateMerchantRequestDTO;
use App\Exceptions\BusinessException;
use App\HttpRepositories\Prm\CompanyHttpRepository;
use App\Models\MerchantRequest;

class UpdateMerchantRequestUseCase
{
    public function __construct(
        private FindMerchantRequestByIdUseCase $findMerchantRequestByIdUseCase,
        private CompanyHttpRepository $companyHttpRepository
    ) {
    }

    public function execute(int $id, UpdateMerchantRequestDTO $merchantRequestDTO): MerchantRequest
    {
        $merchant_request = $this->findMerchantRequestByIdUseCase->execute($id);

        if ($this->companyHttpRepository->getCompanyByName($merchantRequestDTO->getName())) {
            throw new BusinessException('Указанное имя компании уже занято', 'company_name_exists', 400);
        }

        $merchant_request->name = $merchantRequestDTO->getName();
        $merchant_request->user_name = $merchantRequestDTO->getUserName();
        $merchant_request->user_phone = $merchantRequestDTO->getUserPhone();
        $merchant_request->legal_name = $merchantRequestDTO->getLegalName();
        $merchant_request->legal_name_prefix = $merchantRequestDTO->getLegalNamePrefix();
        $merchant_request->categories = $merchantRequestDTO->getCategories();
        $merchant_request->region = $merchantRequestDTO->getRegion();
        $merchant_request->district = $merchantRequestDTO->getDistrict();
        $merchant_request->stores_count = $merchantRequestDTO->getStoresCount();
        $merchant_request->merchant_users_count = $merchantRequestDTO->getMerchantUsersCount();
        $merchant_request->approximate_sales = $merchantRequestDTO->getApproximateSales();

        if ((
            $merchant_request->user_name &&
                $merchant_request->legal_name &&
                $merchant_request->legal_name_prefix &&
                $merchant_request->user_phone &&
                $merchant_request->name &&
                $merchant_request->region &&
                $merchant_request->approximate_sales
        ) === true) {
            $merchant_request->main_completed = true;
        }

        $merchant_request->save();

        return $merchant_request;
    }
}
