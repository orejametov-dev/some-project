<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiGateway\AzoMerchants\Merchants;

use App\Exceptions\BusinessException;
use App\Filters\Merchant\MerchantIdFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiPrm\Merchants\StoreAdditionalAgreements;
use App\Models\AdditionalAgreement;
use App\Models\Merchant;
use App\Models\MerchantInfo;
use App\Services\WordService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AdditionalAgreementsController extends Controller
{
    public function index(Request $request)
    {
        $additional_agreements = AdditionalAgreement::query()
            ->filterRequest($request, [MerchantIdFilter::class]);

        if ($request->query('object') == 'true') {
            return $additional_agreements->first();
        }

        return $additional_agreements->paginate($request->query('per_page') ?? 15);
    }

    public function show($id)
    {
        return AdditionalAgreement::query()->findOrFail($id);
    }

    public function store(StoreAdditionalAgreements $request)
    {
        if ($request->input('document_type') === AdditionalAgreement::LIMIT && $request->input('limit') === null) {
            throw new BusinessException('Лимит должен быть передан', 'params_not_exists', 400);
        }

        if (!MerchantInfo::query()->where('merchant_id', $request->input('merchant_id'))->exists()) {
            throw new BusinessException('Нет основного договора');
        }

        $merchant = Merchant::query()->findOrFail($request->input('merchant_id'));
        $additional_agreement = new AdditionalAgreement($request->validated());
        $additional_agreement->merchant()->associate($merchant);

        $additional_agreement->save();

        return $additional_agreement;
    }

    public function update(StoreAdditionalAgreements $request, $id)
    {
        $additional_agreement = AdditionalAgreement::query()->findOrFail($id);

        $additional_agreement->fill($request->validated());
        $additional_agreement->save();

        return $additional_agreement;
    }

    public function getAdditionalAgreementDoc($id, WordService $wordService): BinaryFileResponse
    {
        $additional_agreement = AdditionalAgreement::query()->find($id);

        if ($additional_agreement === null) {
            throw new BusinessException('Дополнительное соглашение не найдено', 'object_not_found', 404);
        }

        $merchant_info = MerchantInfo::query()->where('merchant_id', $additional_agreement->merchant_id)->firstOrFail();

        $additional_agreement_file = null;

        if ($additional_agreement->document_type === AdditionalAgreement::LIMIT) {
            $additional_agreement_file = $wordService->createAdditionalAgreement($additional_agreement, $merchant_info, 'app/additional_agreement.docx');
        }

        if ($additional_agreement->document_type === AdditionalAgreement::VAT) {
            $additional_agreement_file = $wordService->createAdditionalAgreement($additional_agreement, $merchant_info, 'app/additional_agreement_vat.docx');
        }

        if ($additional_agreement->document_type === AdditionalAgreement::DELIVERY) {
            $additional_agreement_file = $wordService->createAdditionalAgreement($additional_agreement, $merchant_info, 'app/additional_agreement_delivery.docx');
        }

        if ($additional_agreement_file === null) {
            throw new  BusinessException('Не правильный тип документа', 'type_not_exists', 400);
        }

        return response()->download(storage_path($additional_agreement_file))->deleteFileAfterSend();
    }

    public function delete($id)
    {
        $additional_agreement = AdditionalAgreement::query()->findOrFail($id);
        $additional_agreement->delete();

        return response()->json(['message' => 'Успешно удалено']);
    }
}
