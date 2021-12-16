<?php

namespace App\Modules\Merchants\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Log extends Eloquent
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'logs';
    protected $fillable = [
        'name',
        'started_at',
        'finished_at',
        'diff'
    ];

}
