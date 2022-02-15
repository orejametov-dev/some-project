<?php

namespace App\Http\Requests\ApiPrm\Competitors;

use Illuminate\Foundation\Http\FormRequest;

class CompetitorsRequest extends FormRequest
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
            'competitor_id' => 'required|integer',
            'volume_sales' => 'required|integer',
            'percentage_approve' => 'required|integer',
            'partnership_at' => 'required|date',
        ];
    }
}
