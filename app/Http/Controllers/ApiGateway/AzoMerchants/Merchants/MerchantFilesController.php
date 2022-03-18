<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiGateway\AzoMerchants\Merchants;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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

    public function upload(Request $request)
    {
        $this->validate($request, [
            'file_type' => [
                'required',
                'string',
                'max:100',
                Rule::in(array_keys(File::$file_types)),
            ],
            'file' => 'required|file|mimes:jpeg,bmp,png,svg,jpg,pdf',
            'merchant_id' => 'required|integer|min:0',
        ]);
        /** @var Merchant $merchant */
        $merchant = Merchant::query()->findOrFail($request->input('merchant_id'));
        $merchant_file = $merchant->uploadFile($request->file('file'), $request->input('file_type'));

        return $merchant_file;
    }

    public function delete($merchant_id, $file_id)
    {
        /** @var Merchant $merchant */
        $merchant = Merchant::query()->findOrFail($merchant_id);
        $merchant->deleteFile($file_id);

        return response()->json(['message' => 'Файл успешно удалён.']);
    }
}
