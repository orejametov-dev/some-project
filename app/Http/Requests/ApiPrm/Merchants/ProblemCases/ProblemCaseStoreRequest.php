<?php

namespace App\Http\Requests\ApiPrm\Merchants\ProblemCases;

use Illuminate\Foundation\Http\FormRequest;

class ProblemCaseStoreRequest extends FormRequest
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
            'credit_number' => 'required_without:application_id|string',
            'application_id' => 'required_without:credit_number|integer',
            'description' => 'required'
        ];
    }
}
