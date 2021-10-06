<?php

namespace App\Modules\AlifshopMerchants\DTO;

class AlifshopMerchantDTO
{
    public int $maintainer_id;
    public int $company_id;

    public function __construct(
        int $maintainer_id,
        int $company_id
    )
    {
        $this->maintainer_id = $maintainer_id;
        $this->company_id = $company_id;
    }
}
