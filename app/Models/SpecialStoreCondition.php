<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $store_id
 * @property int $condition_id
 */
class SpecialStoreCondition extends Model
{
    use HasFactory;
    protected $table = 'special_store_conditions';
}
