<?php

declare(strict_types=1);

namespace App\Http\Requests\ApiPrmGateway\Competitors;

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
            'volume_sales' => 'nullable|integer|max:100',
            'percentage_approve' => 'nullable|integer|max:100',
            'partnership_at' => 'nullable|date',
        ];
    }
}
