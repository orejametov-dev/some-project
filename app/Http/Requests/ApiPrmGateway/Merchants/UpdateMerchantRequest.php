<?php

declare(strict_types=1);

namespace App\Http\Requests\ApiPrmGateway\Merchants;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMerchantRequest extends FormRequest
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
            'name' => 'required|max:255',
            'legal_name' => 'required|max:255',
            'legal_name_prefix' => 'required|string',
            'token' => 'required|max:255',
        ];
    }
}
