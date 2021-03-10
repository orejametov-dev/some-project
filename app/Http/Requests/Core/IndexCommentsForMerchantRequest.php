<?php

namespace App\Http\Requests\Core;

use Illuminate\Foundation\Http\FormRequest;

class IndexCommentsForMerchantRequest extends IndexComments
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
        return array_merge(parent::rules(), [
            'commentable_type' => 'string|in:merchant_requests',
        ]);
    }
}
