<?php

declare(strict_types=1);

namespace App\Http\Requests\ApiPrm\Merchants;

use App\Models\AdditionalAgreement;
use Illuminate\Foundation\Http\FormRequest;

class StoreAdditionalAgreements extends FormRequest
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
            'limit' => 'nullable|integer',
            'registration_date' => 'required|date_format:Y-m-d',
            'number' => 'required',
            'merchant_id' => 'required|integer',
            'document_type' => 'required|string|in:'
                . AdditionalAgreement::LIMIT . ','
                . AdditionalAgreement::VAT . ','
                . AdditionalAgreement::DELIVERY,
        ];
    }
}
