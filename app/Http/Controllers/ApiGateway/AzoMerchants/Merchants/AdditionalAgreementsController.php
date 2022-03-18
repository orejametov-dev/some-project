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
        $this->validate($request, [
            'merchant_id' => 'required|integer',
        ]);

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

    public function getAdditionalAgreementDoc(WordService $wordService, $id)
    {
        $additional_agreement = AdditionalAgreement::query()->findOrFail($id);
        $merchant_info = MerchantInfo::query()->where('merchant_id', $additional_agreement->merchant_id)->firstOrFail();
        $additional_agreement_file = $wordService->createAdditionalAgreement($additional_agreement, $merchant_info, 'app/additional_agreement.docx');

        return response()->download(storage_path($additional_agreement_file))->deleteFileAfterSend();
    }

    public function delete($id)
    {
        $additional_agreement = AdditionalAgreement::query()->findOrFail($id);
        $additional_agreement->delete();

        return response()->json(['message' => 'Успешно удалено']);
    }
}
