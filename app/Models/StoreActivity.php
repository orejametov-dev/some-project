<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $store_type
 * @property int $store_id
 * @property int $activity_reason_id
 * @property bool $active
 * @property string $created_by_name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class StoreActivity extends Model
{
    use HasFactory;
    protected $table = 'store_activities';
}
