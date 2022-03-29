<?php

declare(strict_types=1);

namespace App\DTOs\MerchantInfos;

use Alifuz\Utils\Entities\AbstractEntity;

final class StoreMerchantInfoDTO extends AbstractEntity
{
    public function __construct(
        private int $merchant_id,
        private string $director_name,
        private string $phone,
        private string $vat_number,
        private string $mfo,
        private string $tin,
        private string $oked,
        private string $bank_account,
        private string $bank_name,
        private string $address,
    ) {
    }

    /**
     * @return int
     */
    public function getMerchantId(): int
    {
        return $this->merchant_id;
    }

    /**
     * @return string
     */
    public function getDirectorName(): string
    {
        return $this->director_name;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @return string
     */
    public function getVatNumber(): string
    {
        return $this->vat_number;
    }

    /**
     * @return string
     */
    public function getMfo(): string
    {
        return $this->mfo;
    }

    /**
     * @return string
     */
    public function getTin(): string
    {
        return $this->tin;
    }

    /**
     * @return string
     */
    public function getOked(): string
    {
        return $this->oked;
    }

    /**
     * @return string
     */
    public function getBankAccount(): string
    {
        return $this->bank_account;
    }

    /**
     * @return string
     */
    public function getBankName(): string
    {
        return $this->bank_name;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param array<mixed> $data
     */
    public static function fromArray(array $data): static
    {
        return new static(
            self::parseInt($data['merchant_id']),
            self::parseString($data['director_name']),
            self::parseString($data['phone']),
            self::parseString($data['vat_number']),
            self::parseString($data['mfo']),
            self::parseString($data['tin']),
            self::parseString($data['oked']),
            self::parseString($data['bank_account']),
            self::parseString($data['bank_name']),
            self::parseString($data['address'])
        );
    }

    /**
     * @return mixed[]
     */
    public function jsonSerialize()
    {
        return [
            'merchant_id' => $this->merchant_id,
            'director_name' => $this->director_name,
            'phone' => $this->phone,
            'vat_number' => $this->vat_number,
            'mfo' => $this->mfo,
            'tin' => $this->tin,
            'oked' => $this->oked,
            'bank_account' => $this->bank_account,
            'bank_name' => $this->bank_name,
            'address' => $this->address,
        ];
    }
}
