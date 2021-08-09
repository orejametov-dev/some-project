<?php


namespace App\Modules\Merchants\DTO\Merchants;


use Illuminate\Http\Request;
use App\Modules\Merchants\Models\Request as MerchantRequest;
use Illuminate\Support\Str;

class MerchantsDTO
{
    public string $name;
    public string $legal_name;
    public string $token;
    public string $alifshop_slug;
    public ?string $information;
    public int $maintainer_id;

    public function __construct(
        string $name,
        string $legal_name,
        ?string $information,
        int $maintainer_id
    )
    {
        $this->name = $name;
        $this->legal_name = $legal_name;
        $this->token = Str::uuid();
        $this->alifshop_slug = Str::slug($this->name);
        $this->information = $information;
        $this->maintainer_id = $maintainer_id;
    }

    public function getDataByRequest(Request $request)
    {

    }

    public function getDataByMerchantRequest(MerchantRequest $merchantRequest)
    {

    }
}
