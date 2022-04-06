<?php

declare(strict_types=1);

namespace App\Http\Requests\ApiPrmGateway\Comments;

use Illuminate\Foundation\Http\FormRequest;

class IndexComments extends FormRequest
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
            'commentable_type' => 'string|in:problem_case_for_prm,problem_case_for_merchant',
            'commentable_id' => 'integer',
        ];
    }
}
