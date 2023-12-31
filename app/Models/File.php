<?php

declare(strict_types=1);

namespace App\Models;

use function config;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class MerchantFile.
 *
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
 * @property-read MerchantRequest $request
 * @method static Builder|File newModelQuery()
 * @method static Builder|File newQuery()
 * @method static Builder|File query()
 */
class File extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'merchant_files';
    public static array $file_types = [
        'passport' => 'Паспорт',
        'law_registration_doc' => 'Свидетельство о регистрации юр лица',
        'director_order' => 'Приказ о становлении директором',
        'vat_registration' => 'Свидетельство и регистрации ндс',
        'certificate_file' => 'Сертификат соответствия ',
        'contract_file' => 'файл договора',
        'additional_agreement_file' => 'файл доп соглашения',
        'order_agreement_file' => 'файл договор поручения',
    ];

    public static array $registration_file_types = [
        'passport' => [
            'name' => 'Скан паспорта директора',
            'lang' => [
                'uz' => 'Rahbar passportining skan varianti',
                'ru' => 'Скан паспорта директора',
            ],
        ],
        'law_registration_doc' => [
            'name' => 'Гувохнома',
            'lang' => [
                'uz' => 'Guvohnoma',
                'ru' => 'Гувохнома',
            ],
        ],
        'vat_registration' => [
            'name' => 'НДС Гувохнома',
            'lang' => [
                'uz' => 'NDS Guvohnoma',
                'ru' => 'НДС Гувохнома',
            ],
        ],
        'director_order' => [
            'name' => 'Копия приказа директора',
            'lang' => [
                'uz' => 'Rahbar qarorining nusxasi',
                'ru' => 'Копия приказа директора',
            ],
        ],
        'certificate_file' => [
            'name' => 'Копия приказа директора',
            'lang' => [
                'uz' => 'Mahsulotning muvofiqligi sertifikati',
                'ru' => 'Сертификат соответствия товара',
            ],
        ],
        'good_prices' => [
            'name' => 'Копия приказа директора',
            'lang' => [
                'uz' => 'Mahsulotlarning narxlari .xlsx (Excel) formatda',
                'ru' => 'Цены на товары в формате .xlsx (Экзель)',
            ],
        ],
        'store_photo' => [
            'name' => 'Фото магазина',
            'lang' => [
                'uz' => 'Do`kon fotosuratlari',
                'ru' => 'Фото магазина',
            ],
        ],
    ];

    protected $appends = ['link'];

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }

    public function request(): BelongsTo
    {
        return $this->belongsTo(MerchantRequest::class);
    }

    public function getLinkAttribute(): string
    {
        return config('local_services.services_storage.domain') . $this->url;
    }
}
