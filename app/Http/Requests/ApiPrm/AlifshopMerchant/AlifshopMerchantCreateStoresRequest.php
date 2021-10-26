<?php

namespace App\Http\Requests\ApiPrm\AlifshopMerchant;

use App\Services\RegionService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AlifshopMerchantCreateStoresRequest extends FormRequest
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
            'name' => 'required|max:255',
            'alifshop_merchant_id' => 'required|numeric',
            'address' => 'nullable|string',
            'responsible_person' => 'required_with:responsible_person_phone|nullable|string',
            'responsible_person_phone' => 'required_with:responsible_person|nullable|digits:12',
            'region' => [
                'required',
                Rule::in(RegionService::getKeys()),
            ],
        ];
    }
}
