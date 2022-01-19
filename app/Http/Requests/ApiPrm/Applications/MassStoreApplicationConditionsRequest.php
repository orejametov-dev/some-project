<?php

namespace App\Http\Requests\ApiPrm\Applications;

use Illuminate\Foundation\Http\FormRequest;

class MassStoreApplicationConditionsRequest extends FormRequest
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
            'special_offer' => 'nullable|string',
            'event_id' => 'nullable|integer',
            'post_merchant' => 'required|boolean',
            'post_alifshop' => 'required|boolean',
            'merchant_ids' => 'required|array',
            'template_ids' => 'required|array',
            'started_at' => 'nullable|date_format:Y-m-d',
            'finished_at' => 'nullable|date_format:Y-m-d',
        ];
    }
}
