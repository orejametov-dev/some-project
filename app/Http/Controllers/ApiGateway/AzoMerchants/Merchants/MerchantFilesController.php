<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiGateway\AzoMerchants\Merchants;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiPrm\Merchants\UploadMerchantFileRequest;
use App\Models\File;
use App\UseCases\Merchants\DeleteMerchantFileUseCase;
use App\UseCases\Merchants\UploadMerchantFileUseCase;
use Illuminate\Http\Request;

class MerchantFilesController extends Controller
{
    public function index(Request $request)
    {
        $filesQuery = File::query();

        if ($merchant_id = $request->query('merchant_id')) {
            $filesQuery->where('merchant_id', $merchant_id);
        }

        if ($request->query('object') == 'true') {
            return $filesQuery->first();
        }

        return $filesQuery->paginate($request->query('per_page') ?? 15);
    }

    public function upload(UploadMerchantFileRequest $request, UploadMerchantFileUseCase $uploadMerchantFileUseCase)
    {
        return $uploadMerchantFileUseCase->execute((int) $request->input('merchant_id'), $request->input('file_type'), $request->file('file'));
    }

    public function delete($merchant_id, $file_id, DeleteMerchantFileUseCase $deleteMerchantFileUseCase)
    {
        $deleteMerchantFileUseCase->execute($merchant_id, $file_id);

        return response()->json(['message' => 'Файл успешно удалён.']);
    }
}
