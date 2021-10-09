<?php

namespace App\Http\Requests\ApiPrm\AlifshopMerchant;

use App\Services\RegionService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AlifshopMerchantStoreStoresRequest extends FormRequest
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
            'merchant_id' => 'required|numeric',
            'region' => [
                'required',
                Rule::in(RegionService::getKeys()),
            ],
        ];
    }
}
