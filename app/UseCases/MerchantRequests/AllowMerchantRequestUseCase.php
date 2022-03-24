<?php

declare(strict_types=1);

namespace App\UseCases\MerchantRequests;

use App\DTOs\MerchantInfos\StoreMerchantInfoDTO;
use App\Enums\MerchantRequestStatusEnum;
use App\Exceptions\BusinessException;
use App\HttpRepositories\Prm\CompanyHttpRepository;
use App\Models\File;
use App\Models\Merchant;
use App\Models\MerchantInfo;
use App\Models\MerchantRequest;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;

class AllowMerchantRequestUseCase
{
    public function __construct(
        private FindMerchantRequestByIdUseCase $findMerchantRequestByIdUseCase,
        private CompanyHttpRepository $companyHttpRepository,
    ) {
    }

    public function execute(int $id): MerchantRequest
    {
        $merchant_request = $this->findMerchantRequestByIdUseCase->execute($id);

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
            phone: $merchant_request->phone,
            vat_number: $merchant_request->vat_number,
            mfo: $merchant_request->mfo,
            tin: $merchant_request->tin,
            oked: $merchant_request->oked,
            bank_account: $merchant_request->bank_account,
            bank_name: $merchant_request->bank_name,
            address: $merchant_request->address
        ));

        DB::transaction(function () use ($merchantInfo, $merchant, $merchant_request) {
            $merchant->save();

            $merchantInfo->save();

            // вот тут немножко не понятно как быть?
            // вынести на отдельный use case ?
            File::query()->where('request_id', $merchant_request->id)->update(['merchant_id' => $merchant->id]);
            $ids = Tag::query()->whereIn('title', $merchant_request->categories)->pluck('id');
            $merchant->tags()->attach($ids);
            $merchant_request->setStatus(MerchantRequestStatusEnum::ALLOWED());
            $merchant_request->save();
        });

        $this->companyHttpRepository->setStatusExist($company->id);

        return $merchant_request;
    }
}
