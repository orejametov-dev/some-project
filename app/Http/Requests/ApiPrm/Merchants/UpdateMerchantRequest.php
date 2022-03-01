<?php

namespace App\Http\Requests\ApiPrm\Merchants;

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
            'alifshop_slug' => 'required|max:255',
            'information' => 'nullable|string',
            'min_application_price' => 'required|integer',
        ];
    }
}
