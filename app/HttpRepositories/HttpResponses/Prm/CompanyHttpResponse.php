<?php


namespace App\HttpRepositories\HttpResponses\Prm;


use App\HttpRepositories\HttpResponses\AbstractHttpResponse;

class CompanyHttpResponse extends AbstractHttpResponse
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
