<?php


namespace App\Services;

use App\Modules\Merchants\Models\AdditionalAgreement;
use App\Modules\Merchants\Models\MerchantInfo;
use Carbon\Carbon;
use NumberFormatter;
use PhpOffice\PhpWord\Exception\CopyFileException;
use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;
use PhpOffice\PhpWord\TemplateProcessor;

class WordService
{
    /*
     *      'legal_name' ФИО,
            'director_name' Юридическое лицо,
            'phone' телефон,
            'vat_number' Регистрационный. код НДС,
            'mfo' МФО,
            'tin' ИНН,
            'oked' ОКЭД,
            'address' адрес,
            'bank_account' Рассчетный счет,
            'bank_name' Наименование банка,
            'contract_number' номер договора,
     */
    /**
     * @throws CopyFileException
     * @throws CreateTemporaryFileException
     */
    public function createContract(MerchantInfo $merchant_info, string $contract_path): string
    {
        $contract_file = storage_path($contract_path);
        $contract_template = new TemplateProcessor($contract_file);
        $current_date = $merchant_info->contract_date;
        $legal_name_prefix = LegalNameService::findNamePrefix($merchant_info->legal_name_prefix);

        $contract_template->setValue('date', Carbon::parse($current_date)->translatedFormat("«d» F Y"));

        $contract_template->setValue('legal_name', $merchant_info->legal_name);
        $contract_template->setValue('legal_name_prefix', $legal_name_prefix['body_ru']['value']);
        $contract_template->setValue('director_name', $merchant_info->director_name);
        $contract_template->setValue('phone', '+' .
            mb_strcut($merchant_info->phone, 0,5) . ' ' .
            mb_strcut($merchant_info->phone,5,3) . '-' .
            wordwrap(mb_strcut($merchant_info->phone,8),2,'-',true));
        $contract_template->setValue('vat_number', $merchant_info->vat_number);
        $contract_template->setValue('mfo', $merchant_info->mfo);
        /** @phpstan-ignore-next-line  */
        $contract_template->setValue('tin', number_format($merchant_info->tin,0,'',' '));
        $contract_template->setValue('oked', wordwrap($merchant_info->oked,2,'.',true));
        $contract_template->setValue('address', $merchant_info->address);
        $contract_template->setValue('bank_account', wordwrap($merchant_info->bank_account,4,' ',true));
        $contract_template->setValue('bank_name', $merchant_info->bank_name);
        $contract_template->setValue('contract_number', $merchant_info->contract_number);


        $contract_file_name = "app/prm_merchant_" . uniqid('contract') . ".docx";

        $contract_template->saveAs(storage_path($contract_file_name));

        return $contract_file_name;
    }

    /*
     * @return string
     * @throws CopyFileException
     * @throws CreateTemporaryFileException
     */
    public function createAdditionalAgreement(
        AdditionalAgreement $additional_agreement,
        MerchantInfo $merchant_info,
        string $additional_agreement_path
    ): string
    {
        $number_text_formatter = new NumberFormatter('ru', NumberFormatter::SPELLOUT);

        $contract_file = storage_path($additional_agreement_path);
        $contract_template = new TemplateProcessor($contract_file);
        $current_date = $merchant_info->contract_date;
        $limit = $additional_agreement->limit / 100;
        $legal_name_prefix = LegalNameService::findNamePrefix($merchant_info->legal_name_prefix);

        $contract_template->setValue('current_date', Carbon::parse($current_date)->translatedFormat("«d» F Y"));

        $contract_template->setValue('number', $additional_agreement->number);
        $contract_template->setValue('registration_date', $additional_agreement->registration_date);
        $contract_template->setValue('limit', $limit);
        $contract_template->setValue('limit_text', $number_text_formatter->format($limit));
        $contract_template->setValue('limit_expired_at', Carbon::parse($additional_agreement->limit_expired_at)->format('Y-m-d'));

        /*Merchant Infos fields*/
        $contract_template->setValue('legal_name', $merchant_info->legal_name);
        $contract_template->setValue('legal_name_prefix', $legal_name_prefix['body_ru']['value']);
        $contract_template->setValue('director_name', $merchant_info->director_name);
        $contract_template->setValue('phone', '+' .
            mb_strcut($merchant_info->phone, 0,5) . ' ' .
            mb_strcut($merchant_info->phone,5,3) . '-' .
            wordwrap(mb_strcut($merchant_info->phone,8),2,'-',true));
        $contract_template->setValue('vat_number', $merchant_info->vat_number);
        $contract_template->setValue('mfo', $merchant_info->mfo);
        /** @phpstan-ignore-next-line  */
        $contract_template->setValue('tin', number_format($merchant_info->tin,0,'',' '));
        $contract_template->setValue('oked', wordwrap($merchant_info->oked,2,'.',true));
        $contract_template->setValue('address', $merchant_info->address);
        $contract_template->setValue('bank_account', wordwrap($merchant_info->bank_account,4,' ',true));
        $contract_template->setValue('bank_name', $merchant_info->bank_name);
        $contract_template->setValue('contract_number', $merchant_info->contract_number);
        $contract_template->setValue('contract_date', Carbon::parse($merchant_info->contract_date)->format('d-m-Y'));

        $contract_file_name = "app/additional_agreement_" . uniqid('contract') . ".docx";

        $contract_template->saveAs(storage_path($contract_file_name));

        return $contract_file_name;
    }
}
