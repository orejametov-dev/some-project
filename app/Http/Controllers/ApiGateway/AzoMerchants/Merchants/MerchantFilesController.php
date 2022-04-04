<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiGateway\AzoMerchants\Merchants;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiPrm\Merchants\UploadMerchantFileRequest;
use App\Http\Resources\ApiGateway\Files\FileResource;
use App\Models\File;
use App\UseCases\Merchants\DeleteMerchantFileUseCase;
use App\UseCases\Merchants\UploadMerchantFileUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MerchantFilesController extends Controller
{
    public function index(Request $request): JsonResource
    {
        $filesQuery = File::query();

        if ($merchant_id = $request->query('merchant_id')) {
            $filesQuery->where('merchant_id', $merchant_id);
        }

        if ($request->query('object') == 'true') {
            return new FileResource($filesQuery->first());
        }

        return FileResource::collection($filesQuery->paginate($request->query('per_page') ?? 15));
    }

    public function upload(UploadMerchantFileRequest $request, UploadMerchantFileUseCase $uploadMerchantFileUseCase): FileResource
    {
        $file = $uploadMerchantFileUseCase->execute((int) $request->input('merchant_id'), $request->input('file_type'), $request->file('file'));

        return new FileResource($file);
    }

    public function delete(int $merchant_id, int $file_id, DeleteMerchantFileUseCase $deleteMerchantFileUseCase): JsonResponse
    {
        $deleteMerchantFileUseCase->execute($merchant_id, $file_id);

        return new JsonResponse(['message' => 'Файл успешно удалён.']);
    }
}
