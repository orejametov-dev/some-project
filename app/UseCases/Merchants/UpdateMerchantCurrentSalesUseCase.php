<?php

declare(strict_types=1);

namespace App\UseCases\Merchants;

use App\HttpRepositories\Core\CoreHttpRepository;
use App\Models\AdditionalAgreement;
use App\Models\Merchant;

class UpdateMerchantCurrentSalesUseCase
{
    public function __construct(
        private CoreHttpRepository $coreHttpRepository,
        private FindMerchantByIdUseCase $findMerchantByIdUseCase,
    ) {
    }

    public function execute(): void
    {
        $percentage_of_limit = Merchant::$percentage_of_limit;

        $amount_of_merchants = $this->coreHttpRepository->getAmountOfMerchantSales();

        foreach ($amount_of_merchants->getEntities() as $amount_of_merchant) {
            $merchant = $this->findMerchantByIdUseCase->execute($amount_of_merchant->getMerchantId());

            $merchant->current_sales = $amount_of_merchant->getDiscountedAmount();
            if ($merchant_info = $merchant->merchant_info) {
                $total_limit = $merchant_info->limit;
                $rest_limit = $merchant_info->limit - $merchant->current_sales;
                $merchant_info->rest_limit = $rest_limit > 0 ? $rest_limit : 0;
                if ($merchant->additional_agreements()->where('document_type', AdditionalAgreement::LIMIT)->exists()) {
                    $additional_agreements = $merchant->additional_agreements;
                    foreach ($additional_agreements as $additional_agreement) {
                        $total_limit += $additional_agreement->limit;
                        $rest_limit = $total_limit - $merchant->current_sales;
                        $additional_agreement->rest_limit = $rest_limit > 0 ? $rest_limit : 0;
                        $additional_agreement->save();
                    }
                }
                $merchant_info->save();
            }
            $merchant->save();
        }

        Merchant::query()
            ->leftJoin('merchant_infos', 'merchants.id', '=', 'merchant_infos.merchant_id')
            ->whereRaw("IFNULL(merchant_infos.limit,0) {$percentage_of_limit} <= merchants.current_sales")
            ->whereNull('merchant_infos.limit_expired_at')
            ->update(['merchant_infos.limit_expired_at' => now()]);

        Merchant::query()
            ->leftJoin('merchant_infos', 'merchants.id', '=', 'merchant_infos.merchant_id')
            ->leftJoin('merchant_additional_agreements', 'merchants.id', '=', 'merchant_additional_agreements.merchant_id')
            ->whereRaw('merchant_additional_agreements.document_type = ' . AdditionalAgreement::LIMIT)
            ->whereRaw("(IFNULL(merchant_infos.limit, 0) + IFNULL(merchant_additional_agreements.limit, 0)) $percentage_of_limit <= merchants.current_sales")
            ->whereNull('merchant_additional_agreements.limit_expired_at')
            ->update(['merchant_additional_agreements.limit_expired_at' => now()]);
    }
}
