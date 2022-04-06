<?php

declare(strict_types=1);

namespace App\Http\Requests\ApiPrmGateway\MerchantRequests;

use App\Models\File;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MerchantRequestUploadFileRequest extends FormRequest
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
                Rule::in(array_keys(File::$registration_file_types)),
            ],
            'file' => 'required|file|mimes:jpeg,bmp,png,svg,jpg,pdf,xlsx,xls',
        ];
    }
}
