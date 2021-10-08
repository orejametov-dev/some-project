<?php

namespace App\Modules\AlifshopMerchants\Models;

use App\Modules\AlifshopMerchants\Traits\AlifshopMerchantStoreRelationshipsTrait;
use App\Modules\Companies\Models\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property $id
 * @property AlifshopMerchant $alifshop_merchant
 */
class AlifshopMerchantStores extends Model
{
    use HasFactory;
    use AlifshopMerchantStoreRelationshipsTrait;
}
