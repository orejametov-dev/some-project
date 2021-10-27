<?php


namespace App\Modules\Companies\DTO;


class CompanyDTO
{
    public string $name;
    public string $legal_name;
    public string $legal_name_key;

    public function __construct(
        string $name,
        string $legal_name,
        string $legal_name_key
    )
    {
        $this->name = $name;
        $this->legal_name = $legal_name;
        $this->legal_name_key = $legal_name_key;
    }
}
