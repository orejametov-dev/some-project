<?php


namespace App\Modules\Merchants\DTO\Merchants;


use App\Modules\Merchants\Models\Request;

class MerchantInfoDTO
{
    public string $director_name;
    public string $legal_name;
    public string $phone;
    public string $vat_number;
    public string $mfo;
    public string $tin;
    public string $oked;
    public string $bank_account;
    public string $bank_name;
    public string $address;

    /**
     * MerchantInfoDTO constructor.
     * @param string $director_name
     * @param string $legal_name
     * @param string $phone
     * @param string $vat_number
     * @param string $mfo
     * @param string $tin
     * @param string $oked
     * @param string $bank_account
     * @param string $bank_name
     * @param string $address
     */
    public function fromConstructor(
        string $director_name,
        string $legal_name,
        string $phone,
        string $vat_number,
        string $mfo,
        string $tin,
        string $oked,
        string $bank_account,
        string $bank_name,
        string $address
    )
    {
        $this->director_name = $director_name;
        $this->legal_name = $legal_name;
        $this->phone = $phone;
        $this->vat_number = $vat_number;
        $this->mfo = $mfo;
        $this->tin = $tin;
        $this->oked = $oked;
        $this->bank_account = $bank_account;
        $this->bank_name = $bank_name;
        $this->address = $address;

        return $this;
    }

    public function fromMerchantRequest(Request $merchant_request)
    {
        $this->director_name = $merchant_request->director_name;
        $this->legal_name = $merchant_request->legal_name;
        $this->phone = $merchant_request->phone;
        $this->vat_number = $merchant_request->vat_number;
        $this->mfo = $merchant_request->mfo;
        $this->tin = $merchant_request->tin;
        $this->oked = $merchant_request->oked;
        $this->bank_account = $merchant_request->bank_account;
        $this->bank_name = $merchant_request->bank_name;
        $this->address = $merchant_request->address;

        return $this;
    }

}