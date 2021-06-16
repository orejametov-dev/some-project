<?php

namespace App\Modules\Merchants\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MerchantFile
 *
 * @package App\Modules\Partners\Models
 * @property int $id
 * @property string $file_type
 * @property string $mime_type
 * @property int $merchant_id
 * @property int $request_id
 * @property int $size
 * @property int $file_id
 * @property string $url
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read mixed $link
 * @property-read Merchant $merchant
 * @property-read Request $request
 * @method static Builder|File newModelQuery()
 * @method static Builder|File newQuery()
 * @method static Builder|File query()
 * @mixin Eloquent
 */
class File extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'merchant_files';
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

    public static $registration_file_types = [
        'scan_director_passport' => [
            'name' => 'Скан паспорта директора',
            'lang' => [
                'uz' => 'Rahbar passportining skan varianti',
                'ru' => 'Скан паспорта директора'
            ]
        ],
        'certificate' => [
            'name' => 'Гувохнома',
            'lang' => [
                'uz' => 'Guvohnoma',
                'ru' => 'Гувохнома'
            ]
        ],
        'vat_certificate' => [
            'name' => 'НДС Гувохнома',
            'lang' => [
                'uz' => 'NDS Guvohnoma',
                'ru' => 'НДС Гувохнома'
            ]
        ],
        'directors_order_copy' => [
            'name' => 'Копия приказа директора',
            'lang' => [
                'uz' => 'Rahbar qarorining nusxasi',
                'ru' => 'Копия приказа директора'
            ]
        ],
        'product_conformity_certificate' => [
            'name' => 'Копия приказа директора',
            'lang' => [
                'uz' => 'Mahsulotning muvofiqligi sertifikati',
                'ru' => 'Сертификат соответствия товара'
            ]
        ],
        'good_prices' => [
            'name' => 'Копия приказа директора',
            'lang' => [
                'uz' => 'Mahsulotlarning narxlari .xlsx (Excel) formatda',
                'ru' => 'Цены на товары в формате .xlsx (Экзель)'
            ]
        ],
        'store_photo' => [
            'name' => 'Фото магазина',
            'lang' => [
                'uz' => 'Do\'kon fotosuratlari',
                'ru' => 'Фото магазина'
            ]
        ]
    ];

    protected $appends = ['link'];

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function request()
    {
        return $this->belongsTo(Request::class);
    }

    public function getLinkAttribute()
    {
        return config('local_services.services_storage.domain');
    }
}
