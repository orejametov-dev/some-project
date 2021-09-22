<?php


namespace App\Modules\Companies\DTO;


class CompanyDTO
{
    public string $name;
    public string $legal_name;

    public function __construct(
        string $name,
        string $legal_name
    )
    {
        $this->name = $name;
        $this->legal_name = $legal_name;
    }
}
