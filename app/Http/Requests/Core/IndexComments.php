<?php

namespace App\Http\Requests\Core;

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
            'commentable_type' => 'string|in:applications,clients',
            'commentable_id' => 'integer'
        ];
    }
}
