<?php

declare(strict_types=1);

namespace App\UseCases\MerchantRequests;

use Alifuz\Utils\Gateway\Entities\Auth\GatewayAuthUser;
use Alifuz\Utils\Gateway\Entities\GatewayApplication;
use App\DTOs\MerchantRequest\StoreMerchantRequestDTO;
use App\Enums\MerchantRequestStatusEnum;
use App\Exceptions\BusinessException;
use App\HttpRepositories\Prm\CompanyHttpRepository;
use App\Models\MerchantRequest;

class StoreMerchantRequestUseCase
{
    public function __construct(
        private CompanyHttpRepository $companyHttpRepository,
        private GatewayApplication $gatewayApplication,
        private GatewayAuthUser $gatewayAuthUser,
    ) {
    }

    public function execute(StoreMerchantRequestDTO $storeMerchantRequestDTO, bool $fromPrm): MerchantRequest
    {
        $merchant_request = MerchantRequest::query()
            ->where('user_phone', $storeMerchantRequestDTO->getUserPhone())
            ->orderByDesc('id')
            ->first();

        if ($merchant_request && $merchant_request->status_id !== MerchantRequestStatusEnum::TRASH()) {
            throw new BusinessException('Запрос с таким номером телефона уже существует, статус запроса');
            //MerchantRequest::getOneById((int) $merchant_request->status_id)->name
        }

        if ($this->companyHttpRepository->checkCompanyToExistByName($storeMerchantRequestDTO->getName()) === true) {
            throw new BusinessException('Указанное имя компании уже занято', 'object_not_found', 400);
        }

        $merchant_request = new MerchantRequest();
        $merchant_request->user_name = $storeMerchantRequestDTO->getUserName();
        $merchant_request->user_phone = $storeMerchantRequestDTO->getUserPhone();
        $merchant_request->name = $storeMerchantRequestDTO->getName();
        $merchant_request->legal_name = $storeMerchantRequestDTO->getLegalName();
        $merchant_request->legal_name_prefix = $storeMerchantRequestDTO->getLegalNamePrefix();
        $merchant_request->categories = $storeMerchantRequestDTO->getCategories();
        $merchant_request->approximate_sales = $storeMerchantRequestDTO->getApproximateSales();
        $merchant_request->region = $storeMerchantRequestDTO->getRegion();
        $merchant_request->district = $storeMerchantRequestDTO->getDistrict();

        if ($fromPrm === true) {
            $merchant_request->address = $storeMerchantRequestDTO->getAddress();
            $merchant_request->engaged_by_id = $this->gatewayAuthUser->getId();
            $merchant_request->engaged_by_name = $this->gatewayAuthUser->getName();
        }

        $merchant_request->created_from_name = $this->gatewayApplication->getApplication()->getValue();
        $merchant_request->setStatus(MerchantRequestStatusEnum::NEW());

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
