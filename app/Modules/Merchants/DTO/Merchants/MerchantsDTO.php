<?php


namespace App\Modules\Merchants\DTO\Merchants;


use Illuminate\Http\Request;
use App\Modules\Merchants\Models\Request as MerchantRequest;
use Illuminate\Support\Str;

class MerchantsDTO
{
    public int $id;
    public string $name;
    public string $legal_name;
    public string $legal_name_prefix;
    public string $token;
    public string $alifshop_slug;
    public ?string $information;
    public int $maintainer_id;
    public int $company_id;

    public function __construct(
        int $id,
        string $name,
        string $legal_name,
        string $legal_name_prefix,
        ?string $information,
        int $maintainer_id,
        int $company_id
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->legal_name = $legal_name;
        $this->legal_name_prefix = $legal_name_prefix;
        $this->token = Str::uuid();
        $this->alifshop_slug = Str::slug($this->name);
        $this->information = $information;
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
