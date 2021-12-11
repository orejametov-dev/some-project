<?php

namespace App\Http\Requests\ApiPrm\Merchants\ProblemCases;

use Illuminate\Foundation\Http\FormRequest;

class ProblemCaseUpdateRequest extends FormRequest
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
            'manager_comment' => 'nullable|string',
            'merchant_comment' => 'nullable|string',
            'deadline' => 'nullable|date_format:Y-m-d',
        ];
    }
}
