<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\StoreActivity;

class StoreActivityRepository
{
    /**
     * @param StoreActivity $storeActivity
     * @return void
     */
    public function save(StoreActivity $storeActivity): void
    {
        $storeActivity->save();
    }
}
