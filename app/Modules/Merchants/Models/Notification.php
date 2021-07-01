<?php

namespace App\Modules\Merchants\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    public const ALL_TYPE = 'ALL';
    public const CERTAIN_TYPE = 'CERTAIN';

    protected $fillable = [
        'title_uz',
        'title_ru',
        'body_ru',
        'body_uz',
        'start_schedule',
        'end_schedule',
        'type'
    ];

    public function setAllType()
    {
        $this->type = self::ALL_TYPE;
    }

    public function setCertainType()
    {
        $this->type = self::CERTAIN_TYPE;
    }

    public function stores()
    {
        return $this->belongsToMany(Store::class, 'store_notification', 'notification_id', 'store_id');
    }
}
