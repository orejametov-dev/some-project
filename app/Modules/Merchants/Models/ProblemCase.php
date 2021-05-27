<?php

namespace App\Modules\Merchants\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProblemCase extends Model
{
    use HasFactory;

    public function merchant()
    {
        $this->belongsTo(Merchant::class);
    }

    public function store()
    {
        $this->belongsTo(Store::class);
    }

//    public function tag()
//    {
//        $this->be
//    }
}
