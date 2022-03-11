<?php

declare(strict_types=1);

namespace App\Http\Requests\ApiPrm\MerchantRequests;

use App\Http\Requests\ApiMerchantsGateway\Merchants\MerchantRequestStoreMain;

class MerchantRequestUpdateRequest extends MerchantRequestStoreMain
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
        return parent::rules() + [
            'stores_count' => 'nullable|integer',
            'merchant_users_count' => 'nullable|integer',
        ];
    }
}
