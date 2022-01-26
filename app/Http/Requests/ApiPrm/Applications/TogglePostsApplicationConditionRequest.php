<?php

namespace App\Http\Requests\ApiPrm\Applications;

use Illuminate\Foundation\Http\FormRequest;

class TogglePostsApplicationConditionRequest extends FormRequest
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
            'post_alifshop' => 'required|boolean',
            'post_merchant' => 'required|boolean'
        ];
    }
}
