<?php

declare(strict_types=1);

namespace App\Http\Requests\ApiPrm\Applications;

use Illuminate\Foundation\Http\FormRequest;

class StoreApplicationConditions extends FormRequest
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
            'merchant_id' => 'required',
            'store_ids' => 'nullable|array',
            'duration' => 'required|numeric|between:0,24',
            'commission' => 'required|integer|between:0,100',
            'special_offer' => 'nullable|string',
            'event_id' => 'nullable|integer',
            'discount'  => 'required|integer|between:0,100',
            'post_merchant' => 'required|boolean',
            'post_alifshop' => 'required|boolean',
            'started_at' => 'nullable|date_format:Y-m-d',
            'finished_at' => 'nullable|date_format:Y-m-d',
        ];
    }
}
