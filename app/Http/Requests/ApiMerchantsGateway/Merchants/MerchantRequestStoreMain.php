<?php

namespace App\Http\Requests\ApiMerchantsGateway\Merchants;

use Illuminate\Foundation\Http\FormRequest;

class MerchantRequestStoreMain extends FormRequest
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
            'name' => 'required|string',
            'legal_name' => 'required|string',
            'legal_name_prefix' => 'required|string',
            'categories' => 'required|array',
            'approximate_sales' => 'required|integer',
            'region' => 'required|string',
            'district' => 'required|string',
        ];
    }
}
