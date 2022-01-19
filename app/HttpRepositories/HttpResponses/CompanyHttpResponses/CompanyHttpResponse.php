<?php


namespace App\HttpRepositories\HttpResponses\CompanyHttpResponses;


class CompanyHttpResponse
{
    public function __construct(
        public int $id,
        public string $name,
        public string $token,
        public string $legal_name,
        public string $legal_name_prefix
    )
    {
    }
}
