<?php

namespace App\Http\Requests\ApiPrm\ProblemCases;

use App\Modules\Merchants\Models\ProblemCase;
use Illuminate\Foundation\Http\FormRequest;

class ProblemCaseSetStatusRequest extends FormRequest
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
            'status_id' => 'required|integer|in:'
                . ProblemCase::NEW . ','
                . ProblemCase::IN_PROCESS . ','
                . ProblemCase::DONE . ','
                . ProblemCase::FINISHED
        ];
    }
}
