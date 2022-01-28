<?php

namespace App\Http\Requests\ApiPrm\ProblemCases;

use Illuminate\Foundation\Http\FormRequest;

class ProblemCaseSetAssignedRequest extends FormRequest
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
            'assigned_to_id' => 'required|integer',
            'assigned_to_name' => 'required|string',
        ];
    }
}
