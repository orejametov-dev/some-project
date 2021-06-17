<?php


namespace App\Modules\Merchants\DTO\Merchants;


use Illuminate\Http\Request;
use App\Modules\Merchants\Models\Request as MerchantRequest;

class MerchantsDTO
{
    public string $name;
    public string $legal_name;
    public string $token;
    public string $alifshop_slug;
    public string $information;
    public int $maintainer_id;

    public function __construct(
//        $
    )
    {

    }

    public function getDataByRequest(Request $request)
    {

    }

    public function getDataByMerchantRequest(MerchantRequest $merchantRequest)
    {

    }
}
