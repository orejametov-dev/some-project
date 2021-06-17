<?php


namespace App\Modules\Merchants\Services\Merchants;


use App\Modules\Merchants\DTO\Merchants\MerchantsDTO;
use App\Modules\Merchants\Models\Merchant;

class MerchantsService
{
    private $merchantsDTO;

    public function __construct(MerchantsDTO $merchantsDTO)
    {
        $this->merchantsDTO = $merchantsDTO;
    }

    public function create()
    {
        $merchant = new Merchant();
    }
}
