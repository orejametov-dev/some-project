<?php

namespace App\Http\Requests\ApiPrm\Merchants;

use Illuminate\Foundation\Http\FormRequest;

class StoreMerchantInfo extends FormRequest
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
            'legal_name' => 'required|max:255',
            'legal_name_prefix' => 'required|string',
            'director_name' => 'required|max:255',
            'phone' => 'required|digits:12',
            'vat_number' => 'required|digits:12',
            'mfo' => 'required|digits:5',
            'tin' => 'required|digits:9',
            'oked' => 'required|digits:5',
            'address' => 'required',
            'bank_account' => 'required|digits:20',
            'bank_name' => 'required|max:255',
        ];
    }
}
