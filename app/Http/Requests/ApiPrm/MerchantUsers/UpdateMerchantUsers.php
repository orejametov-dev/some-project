<?php

namespace App\Http\Requests\ApiPrm\MerchantUsers;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMerchantUsers extends FormRequest
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
            'permission_applications' => 'nullable|boolean',
            'permission_deliveries' => 'nullable|boolean',
            'permission_orders' => 'nullable|boolean',
            'permission_manager' => 'nullable|boolean',
            'permission_upload_goods' => 'nullable|boolean',
            'store_id' => 'required|integer',
        ];
    }
}
