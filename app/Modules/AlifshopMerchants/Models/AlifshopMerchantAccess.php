<?php

namespace App\Modules\AlifshopMerchants\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * @method static Builder|AlifshopMerchantAccess filterRequest(Request $request)
 * @method static Builder|AlifshopMerchantAccess orderRequest(Request $request, string $default_order_str = 'id:desc')
 * @method static Builder|AlifshopMerchantAccess query()
 */
class AlifshopMerchantAccess extends Model
{
    use HasFactory;
}
