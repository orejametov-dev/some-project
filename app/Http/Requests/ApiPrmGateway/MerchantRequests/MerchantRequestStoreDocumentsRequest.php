<?php

declare(strict_types=1);

namespace App\Http\Requests\ApiPrmGateway\MerchantRequests;

use Illuminate\Foundation\Http\FormRequest;

class MerchantRequestStoreDocumentsRequest extends FormRequest
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
            'director_name' => 'required|max:255|string',
            'phone' => 'required|digits:12',
            'vat_number' => 'required|digits:12',
            'mfo' => 'required|digits:5',
            'tin' => 'required|digits:9',
            'oked' => 'required|digits:5',
            'bank_account' => 'required|digits:20',
            'bank_name' => 'required|max:255',
            'address' => 'required|string',
        ];
    }
}
