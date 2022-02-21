<?php

namespace App\UseCases\MerchantRequests;

use Alifuz\Utils\Gateway\Entities\GatewayApplication;
use App\DTOs\MerchantRequest\StoreMerchantRequestDTO;
use App\Exceptions\BusinessException;
use App\HttpRepositories\Prm\CompanyHttpRepository;
use App\Modules\Merchants\Models\Request as MerchantRequest;
use function response;

class StoreMainMerchantRequestUseCase
{
    public function __construct(
        private CompanyHttpRepository $companyHttpRepository,
        private GatewayApplication $gatewayApplication,
    ) {
    }

    public function execute(StoreMerchantRequestDTO $storeMerchantRequestDTO)
    {
        $merchant_request = MerchantRequest::query()
            ->where('user_phone', $storeMerchantRequestDTO->user_phone)
            ->orderByDesc('id')
            ->first();

        if ($merchant_request && $merchant_request->status_id !== MerchantRequest::TRASH) {
            throw new BusinessException('Запрос с таким номером телефона уже существует, статус запроса '
                . MerchantRequest::getOneById((int) $merchant_request->status_id)->name);
        }

        if ($this->companyHttpRepository->checkCompanyToExistByName($storeMerchantRequestDTO->name) === true) {
            return response()->json(['message' => 'Указанное имя компании уже занято'], 400);
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

        $merchant_request->created_from_name = $this->gatewayApplication->getApplication()->getValue();
        $merchant_request->setStatusNew();

        $merchant_request->save();
        $merchant_request->checkToMainCompleted();

        return $merchant_request;
    }
}
