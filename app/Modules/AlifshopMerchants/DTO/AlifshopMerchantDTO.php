<?php

namespace App\Modules\AlifshopMerchants\DTO;

use Illuminate\Support\Str;

class AlifshopMerchantDTO
{
    public int $id;
    public string $name;
    public string $legal_name;
    public string $token;
    public string $alifshop_slug;
    public ?string $information;
    public int $maintainer_id;
    public int $company_id;

    public function __construct(
        int     $id,
        string  $name,
        string  $legal_name,
        ?string $information,
        int     $maintainer_id,
        int     $company_id
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->legal_name = $legal_name;
        $this->token = Str::uuid();
        $this->alifshop_slug = Str::slug($this->name);
        $this->information = $information;
        $this->maintainer_id = $maintainer_id;
        $this->company_id = $company_id;
    }
}
