<?php

namespace App\Modules\Merchants\DTO\Merchants;

use App\Modules\Merchants\Models\Request as MerchantRequest;
use Illuminate\Http\Request;

class MerchantsDTO
{
    public int $id;
    public string $name;
    public string $legal_name;
    public string $legal_name_prefix;
    public string $token;
    public int $maintainer_id;
    public int $company_id;

    public function __construct(
        int $id,
        string $name,
        string $legal_name,
        string $legal_name_prefix,
        string $token,
        int $maintainer_id,
        int $company_id
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->legal_name = $legal_name;
        $this->legal_name_prefix = $legal_name_prefix;
        $this->token = $token;
        $this->maintainer_id = $maintainer_id;
        $this->company_id = $company_id;
    }

    public function getDataByRequest(Request $request)
    {
    }

    public function getDataByMerchantRequest(MerchantRequest $merchantRequest)
    {
    }
}
