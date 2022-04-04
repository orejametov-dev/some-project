<?php

declare(strict_types=1);

namespace App\Http\Requests\ApiPrm\ProblemCases;

use App\Enums\ProblemCaseStatusEnum;
use Illuminate\Foundation\Http\FormRequest;

class SetProblemCaseStatusRequest extends FormRequest
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
                . ProblemCaseStatusEnum::NEW()->getValue() . ','
                . ProblemCaseStatusEnum::IN_PROCESS()->getValue() . ','
                . ProblemCaseStatusEnum::DONE()->getValue() . ','
                . ProblemCaseStatusEnum::FINISHED()->getValue(),
        ];
    }
}
