<?php

namespace App\Http\Requests\ApiPrm\Applications;

use Illuminate\Foundation\Http\FormRequest;

class UpdateApplicationConditions extends FormRequest
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
            'store_ids' => 'nullable|array',
            'duration' => 'required|numeric|between:0,15',
            'commission' => 'required|integer|between:0,100',
            'special_offer' => 'nullable|string',
            'discount' => 'required|integer|between:0,100'
        ];
    }
}
