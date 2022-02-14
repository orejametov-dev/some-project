<?php

declare(strict_types=1);

namespace App\UseCases\MerchantRequests;

use App\DTOs\MerchantInfos\StoreMerchantInfoDTO;
use App\Exceptions\BusinessException;
use App\HttpRepositories\Prm\CompanyHttpRepository;
use App\Modules\Merchants\Models\File;
use App\Modules\Merchants\Models\Merchant;
use App\Modules\Merchants\Models\MerchantInfo;
use App\Modules\Merchants\Models\Request as MerchantRequest;
use App\Modules\Merchants\Models\Tag;

class AllowMerchantRequestUseCase
{
    public function __construct(
        private CompanyHttpRepository $companyHttpRepository,
    ) {
    }

    public function execute(int $id): MerchantRequest
    {
        $merchant_request = MerchantRequest::find($id);

        if ($merchant_request === null) {
            throw new BusinessException('Запрос на регистарцию не найден', 'object_not_found', 404);
        }

        if ($merchant_request->isOnTraining() === false) {
            throw new BusinessException('Статус заявки должен быть "На обучении"');
        }

        if ($this->companyHttpRepository->checkCompanyToExistByName($merchant_request->name)) {
            throw new BusinessException('Указанное имя компании уже занято');
        }

        if (Merchant::query()->where('name', $merchant_request->name)->exists()) {
            throw new BusinessException('Указанное имя партнера уже занято');
        }

        $company = $this->companyHttpRepository->createCompany(
            name: $merchant_request->name,
            legal_name: $merchant_request->legal_name,
            legal_name_prefix: $merchant_request->legal_name_prefix
        );

        $merchant = Merchant::fromDto($company, $merchant_request->engaged_by_id);

        $merchantInfo = MerchantInfo::fromDTO(new StoreMerchantInfoDTO(
            merchant_id: $merchant->id,
            director_name: $merchant_request->director_name,
            legal_name: $company->legal_name,
            legal_name_prefix: $company->legal_name_prefix,
            phone: $merchant_request->phone,
            vat_number: $merchant_request->vat_number,
            mfo: $merchant_request->mfo,
            tin: $merchant_request->tin,
            oked: $merchant_request->oked,
            bank_account: $merchant_request->bank_account,
            bank_name: $merchant_request->bank_name,
            address: $merchant_request->address
        ));

        \DB::transaction(function () use ($merchantInfo, $merchant, $merchant_request) {
            $merchant->save();

            $merchantInfo->save();

            // вот тут немножко не понятно как быть?
            // вынести на отдельный use case ?
            File::where('request_id', $merchant_request->id)->update(['merchant_id' => $merchant->id]);
            $ids = Tag::whereIn('title', $merchant_request->categories)->pluck('id');
            $merchant->tags()->attach($ids);
            $merchant_request->setStatusAllowed();
            $merchant_request->save();
        });

        $this->companyHttpRepository->setStatusExist($company->id);

        return $merchant_request;
    }
}
