<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * @method static self LIMIT()
 * @method static self VAT()
 * @method static self DELIVERY()
 */
final class AdditionalAgreementDocumentTypeEnum extends CastableEnum
{
    private const LIMIT = 'limit';
    private const VAT = 'vat';
    private const DELIVERY = 'delivery';
}
