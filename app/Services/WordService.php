<?php


namespace App\Services;


use App\Modules\Applications\Models\GoodType;
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
     * @param $merchant_info
     * @param $contract_path
     * @throws CopyFileException
     * @throws CreateTemporaryFileException
     */
    public function createContract($merchant_info, $contract_path)
    {
        $contract_file = storage_path($contract_path);
        $contract_template = new TemplateProcessor($contract_file);
        $current_date = $merchant_info->contract_date;

        $contract_template->setValue('date', Carbon::parse($current_date)->translatedFormat("«d» F Y"));

        $contract_template->setValue('legal_name', $merchant_info->legal_name);
        $contract_template->setValue('director_name', $merchant_info->director_name);
        $contract_template->setValue('phone', $merchant_info->phone);
        $contract_template->setValue('vat_number', $merchant_info->vat_number);
        $contract_template->setValue('mfo', $merchant_info->mfo);
        $contract_template->setValue('tin', $merchant_info->tin);
        $contract_template->setValue('oked', $merchant_info->oked);
        $contract_template->setValue('address', $merchant_info->address);
        $contract_template->setValue('bank_account', $merchant_info->bank_account);
        $contract_template->setValue('bank_name', $merchant_info->bank_name);
        $contract_template->setValue('contract_number', $merchant_info->contract_number);
        $contract_template->setValue('legal_name_prefix', $merchant_info->legal_name_prefix);


        $contract_file_name = "app/prm_merchant_" . uniqid('contract') . ".docx";

        $contract_template->saveAs(storage_path($contract_file_name));

        return $contract_file_name;
    }

    /**
     * @param $additional_agreement
     * @param $additional_agreement_path
     * @return string
     * @throws CopyFileException
     * @throws CreateTemporaryFileException
     */
    public function createAdditionalAgreement($additional_agreement, $merchant_info, $additional_agreement_path)
    {
        $number_text_formatter = new NumberFormatter('ru', NumberFormatter::SPELLOUT);

        $contract_file = storage_path($additional_agreement_path);
        $contract_template = new TemplateProcessor($contract_file);
        $current_date = $merchant_info->contract_date;

        $contract_template->setValue('current_date', Carbon::parse($current_date)->translatedFormat("«d» F Y"));

        $contract_template->setValue('number', $additional_agreement->number);
        $contract_template->setValue('registration_date', $additional_agreement->registration_date);
        $contract_template->setValue('limit', $additional_agreement->limit);
        $contract_template->setValue('limit_text', $number_text_formatter->format($additional_agreement->limit));
        $contract_template->setValue('limit_expired_at', Carbon::parse($additional_agreement->limit_expired_at)->format('Y-m-d'));

        /*Merchant Infos fields*/
        $contract_template->setValue('legal_name', $merchant_info->legal_name);
        $contract_template->setValue('legal_name_prefix', $merchant_info->legal_name_prefix);
        $contract_template->setValue('director_name', $merchant_info->director_name);
        $contract_template->setValue('phone', $merchant_info->phone);
        $contract_template->setValue('vat_number', $merchant_info->vat_number);
        $contract_template->setValue('mfo', $merchant_info->mfo);
        $contract_template->setValue('tin', $merchant_info->tin);
        $contract_template->setValue('oked', $merchant_info->oked);
        $contract_template->setValue('address', $merchant_info->address);
        $contract_template->setValue('bank_account', $merchant_info->bank_account);
        $contract_template->setValue('bank_name', $merchant_info->bank_name);
        $contract_template->setValue('contract_number', $merchant_info->contract_number);
        $contract_template->setValue('contract_date', Carbon::parse($merchant_info->contract_date)->format('d-m-Y'));

        $contract_file_name = "app/additional_agreement_" . uniqid('contract') . ".docx";

        $contract_template->saveAs(storage_path($contract_file_name));

        return $contract_file_name;
    }
}
