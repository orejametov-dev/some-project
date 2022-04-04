<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiGateway\AzoMerchants\Merchants;

use App\DTOs\AdditionalAgreements\StoreAdditionalAgreementDTO;
use App\Filters\Merchant\MerchantIdFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApiPrm\Merchants\StoreAdditionalAgreements;
use App\Http\Resources\ApiGateway\AdditionalAgreement\IndexAdditionalAgreementResource;
use App\Http\Resources\ApiGateway\AdditionalAgreement\StoreAdditionalAgreementResource;
use App\Http\Resources\ApiGateway\AdditionalAgreement\UpdateAdditionalAgreementResource;
use App\Models\AdditionalAgreement;
use App\UseCases\AdditionalAgreements\DeleteAdditionalAgreementUseCase;
use App\UseCases\AdditionalAgreements\GenerateAdditionalAgreementDocUseCase;
use App\UseCases\AdditionalAgreements\StoreAdditionalAgreementUseCase;
use App\UseCases\AdditionalAgreements\UpdateAdditionalAgreementUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AdditionalAgreementsController extends Controller
{
    public function index(Request $request): JsonResource
    {
        $additional_agreements = AdditionalAgreement::query()
            ->filterRequest($request, [MerchantIdFilter::class]);

        if ($request->query('object') == 'true') {
            return IndexAdditionalAgreementResource::collection($additional_agreements->first());
        }

        return IndexAdditionalAgreementResource::collection($additional_agreements->paginate($request->query('per_page') ?? 15));
    }

    public function store(StoreAdditionalAgreements $request, StoreAdditionalAgreementUseCase $storeAdditionalAgreementUseCase): StoreAdditionalAgreementResource
    {
        $additional_agreement = $storeAdditionalAgreementUseCase->execute(StoreAdditionalAgreementDTO::fromArray($request->validated()));

        return new StoreAdditionalAgreementResource($additional_agreement);
    }

    public function update(int $id, StoreAdditionalAgreements $request, UpdateAdditionalAgreementUseCase $updateAdditionalAgreementUseCase): UpdateAdditionalAgreementResource
    {
        $additional_agreement = $updateAdditionalAgreementUseCase->execute($id, StoreAdditionalAgreementDTO::fromArray($request->validated()));

        return new UpdateAdditionalAgreementResource($additional_agreement);
    }

    public function getAdditionalAgreementDoc(int $id, GenerateAdditionalAgreementDocUseCase $generateAdditionalAgreementDocUseCase): BinaryFileResponse
    {
        $file_path = $generateAdditionalAgreementDocUseCase->execute($id);

        return response()->download(storage_path($file_path))->deleteFileAfterSend();
    }

    public function delete(int $id, DeleteAdditionalAgreementUseCase $deleteAdditionalAgreementUseCase): JsonResponse
    {
        $deleteAdditionalAgreementUseCase->execute($id);

        return new JsonResponse(['message' => 'Успешно удалено']);
    }
}
