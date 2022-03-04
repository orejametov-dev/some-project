<?php

namespace App\Http\Requests\ApiPrm\ProblemCases;

use App\Modules\Merchants\Models\ProblemCaseTag;
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
            'description' => 'required',
            'tags' => 'nullable|array',
            'tags.*.name' => 'nullable|string',
            'tags.*.type_id' => 'nullable|integer|in:' . ProblemCaseTag::BEFORE_TYPE . ', ' . ProblemCaseTag::AFTER_TYPE,
        ];
    }
}
