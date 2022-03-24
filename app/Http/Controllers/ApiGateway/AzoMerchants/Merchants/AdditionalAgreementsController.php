<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiGateway\AzoMerchants\Merchants;

use App\DTOs\AdditionalAgreements\StoreAdditionalAgreementDTO;
use App\Filters\Merchant\MerchantIdFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiPrm\Merchants\StoreAdditionalAgreements;
use App\Models\AdditionalAgreement;
use App\UseCases\AdditionalAgreements\DeleteAdditionalAgreementUseCase;
use App\UseCases\AdditionalAgreements\FindAdditionalAgreementUseCase;
use App\UseCases\AdditionalAgreements\GenerateAdditionalAgreementDocUseCase;
use App\UseCases\AdditionalAgreements\StoreAdditionalAgreementUseCase;
use App\UseCases\AdditionalAgreements\UpdateAdditionalAgreementUseCase;
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

    public function show($id, FindAdditionalAgreementUseCase $findAdditionalAgreementUseCase)
    {
        return $findAdditionalAgreementUseCase->execute((int) $id);
    }

    public function store(StoreAdditionalAgreements $request, StoreAdditionalAgreementUseCase $storeAdditionalAgreementUseCase)
    {
        return $storeAdditionalAgreementUseCase->execute(StoreAdditionalAgreementDTO::fromArray($request->validated()));
    }

    public function update($id, StoreAdditionalAgreements $request, UpdateAdditionalAgreementUseCase $updateAdditionalAgreementUseCase)
    {
        return $updateAdditionalAgreementUseCase->execute((int) $id, StoreAdditionalAgreementDTO::fromArray($request->validated()));
    }

    public function getAdditionalAgreementDoc($id, GenerateAdditionalAgreementDocUseCase $generateAdditionalAgreementDocUseCase): BinaryFileResponse
    {
        $file_path = $generateAdditionalAgreementDocUseCase->execute((int) $id);

        return response()->download(storage_path($file_path))->deleteFileAfterSend();
    }

    public function delete($id, DeleteAdditionalAgreementUseCase $deleteAdditionalAgreementUseCase)
    {
        $deleteAdditionalAgreementUseCase->execute((int) $id);

        return response()->json(['message' => 'Успешно удалено']);
    }
}
