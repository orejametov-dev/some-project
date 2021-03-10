<?php

namespace App\Http\Requests\ApiPrm\Merchants;

use Illuminate\Foundation\Http\FormRequest;


class StoreAdditionalAgreements extends FormRequest
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
            'limit' => 'required|integer',
            'registration_date' => 'required|date_format:Y-m-d',
            'number' => 'required',
        ];
    }
}
