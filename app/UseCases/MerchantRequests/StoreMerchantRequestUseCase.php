<?php

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
            ->where('user_phone', $storeMerchantRequestDTO->user_phone)
            ->orderByDesc('id')
            ->first();

        if ($merchant_request && $merchant_request->status_id !== MerchantRequestStatusEnum::TRASH()->getValue()) {
            throw new BusinessException('Запрос с таким номером телефона уже существует, статус запроса');
            //MerchantRequest::getOneById((int) $merchant_request->status_id)->name
        }

        if ($this->companyHttpRepository->checkCompanyToExistByName($storeMerchantRequestDTO->name) === true) {
            throw new BusinessException('Указанное имя компании уже занято', 'object_not_found', 400);
        }

        $merchant_request = new MerchantRequest();
        $merchant_request->user_name = $storeMerchantRequestDTO->user_name;
        $merchant_request->user_phone = $storeMerchantRequestDTO->user_phone;
        $merchant_request->name = $storeMerchantRequestDTO->name;
        $merchant_request->legal_name = $storeMerchantRequestDTO->legal_name;
        $merchant_request->legal_name_prefix = $storeMerchantRequestDTO->legal_name_prefix;
        $merchant_request->categories = $storeMerchantRequestDTO->categories;
        $merchant_request->approximate_sales = $storeMerchantRequestDTO->approximate_sales;
        $merchant_request->region = $storeMerchantRequestDTO->region;
        $merchant_request->district = $storeMerchantRequestDTO->district;

        if ($fromPrm === true) {
            $merchant_request->address = $storeMerchantRequestDTO->address;
            $merchant_request->engaged_by_id = $this->gatewayAuthUser->getId();
            $merchant_request->engaged_by_name = $this->gatewayAuthUser->getName();
        }

        $merchant_request->created_from_name = $this->gatewayApplication->getApplication()->getValue();
        $merchant_request->setStatus(MerchantRequestStatusEnum::NEW());

        $merchant_request->save();
        $merchant_request->checkToMainCompleted();

        return $merchant_request;
    }
}
