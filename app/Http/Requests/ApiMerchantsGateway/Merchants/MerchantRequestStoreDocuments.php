<?php

namespace App\Http\Requests\ApiMerchantsGateway\Merchants;

use Illuminate\Foundation\Http\FormRequest;

class MerchantRequestStoreDocuments extends FormRequest
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
            'token' => 'required|string',
            'director_name' => 'required|max:255',
            'legal_name' => 'required|string',
            'legal_name_key' => 'required|string',
            'phone' => 'required|digits:12',
            'vat_number' => 'required|digits:12',
            'mfo' => 'required|digits:5',
            'tin' => 'required|digits:9',
            'oked' => 'required|digits:5',
            'bank_account' => 'required|digits:20',
            'bank_name' => 'required|max:255',
            'address' => 'required|string'
        ];
    }
}
