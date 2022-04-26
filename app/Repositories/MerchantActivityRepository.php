<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\MerchantActivity;
use Illuminate\Database\Eloquent\Builder;

class MerchantActivityRepository
{
//    private MerchantActivity|Builder $merchantActivity;
//
//    public function __construct()
//    {
//        $this->merchantActivity = MerchantActivity::query();
//    }

    /**
     * @param MerchantActivity $merchantActivity
     * @return void
     */
    public function save(MerchantActivity $merchantActivity): void
    {
        $merchantActivity->save();
    }
}
