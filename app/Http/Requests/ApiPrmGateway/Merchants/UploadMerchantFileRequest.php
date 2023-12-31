<?php

declare(strict_types=1);

namespace App\Http\Requests\ApiPrmGateway\Merchants;

use App\Models\File;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UploadMerchantFileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'file_type' => [
                'required',
                'string',
                'max:100',
                Rule::in(array_keys(File::$file_types)),
            ],
            'file' => 'required|file|mimes:jpeg,bmp,png,svg,jpg,pdf',
            'merchant_id' => 'required|integer|min:0',
        ];
    }
}
