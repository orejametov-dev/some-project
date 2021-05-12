<?php

namespace App\Http\Requests\ApiPrm\MerchantRequests;

use Illuminate\Foundation\Http\FormRequest;

class MerchantRequestStore extends FormRequest
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
            'user_name' => 'required|string',
            'user_phone' => 'required|digits:12',
            'region' => 'required|string',
            'merchant_name' => 'required|string',
            'merchant_information' => 'nullable|string',
            'merchant_legal_name' => 'nullable|string',
        ];
    }
}
