<?php

namespace App\Modules\AlifshopMerchants\Models;

use App\Modules\AlifshopMerchant\Traits\AlifshopMerchantStoreRelationshipsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property AlifshopMerchant $alifshopMerchant
 */
class AlifshopMerchantStores extends Model
{
    use HasFactory;
    use AlifshopMerchantStoreRelationshipsTrait;
}
