<?php

declare(strict_types=1);

namespace App\Http\Requests\ApiPrm\ProblemCases;

use App\Modules\Merchants\Models\ProblemCaseTag;
use Illuminate\Foundation\Http\FormRequest;

class ProblemCaseAttachTagsRequest extends FormRequest
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
            'tags' => 'required|array',
            'tags.*.name' => 'required|string',
            'tags.*.type_id' => 'required|integer|in:' . ProblemCaseTag::BEFORE_TYPE . ', ' . ProblemCaseTag::AFTER_TYPE,
        ];
    }
}
