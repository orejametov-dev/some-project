<?php

namespace App\Modules\Merchants\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MerchantFile
 * @package App\Modules\Partners\Models
 * @property int $id
 * @property string $file_type
 * @property string $mime_type
 * @property int $merchant_id
 * @property int $size
 * @property int $file_id
 * @property string $url
 */


class File extends Model
{
    use HasFactory;
    public $timestamps = false;

    public static $file_types = [
        'passport' => 'Паспорт',
        'law_registration_doc' => 'Свидетельство о регистрации юр лица',
        'director_order' => 'Приказ о становлении директором',
        'vat_registration' => 'Свидетельство и регистрации ндс',
        'certificate_file' => 'Сертификат соответствия ',
        'contract_file' => 'файл договора',
        'additional_agreement_file' => 'файл доп соглашения',
        'order_agreement_file' => 'файл договор поручения',
    ];

    protected $appends = ['link'];

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function getLinkAttribute()
    {
        return config('local_services.services_storage.domain');
    }
}
