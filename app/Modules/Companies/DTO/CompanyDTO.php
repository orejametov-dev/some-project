<?php


namespace App\Modules\Companies\DTO;


class CompanyDTO
{
    public string $name;
    public string $legal_name;
    public string $legal_name_prefix;

    public function __construct(
        string $name,
        string $legal_name,
        string $legal_name_prefix
    )
    {
        $this->name = $name;
        $this->legal_name = $legal_name;
        $this->legal_name_prefix = $legal_name_prefix;
    }
}
