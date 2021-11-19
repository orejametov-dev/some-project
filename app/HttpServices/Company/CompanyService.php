<?php

namespace App\HttpServices\Company;

use Illuminate\Support\Facades\Http;

class CompanyService
{
    public static function getCompanyById($company_id)
    {
        return static::http()->get("companies/$company_id")->throw()->json();
    }

    public static function getCompanyByName($name)
    {
        return static::http()->get('companies/company-by-name', [
            'name' => $name
        ])->throw()->json();
    }

    public static function createCompany(string $name, string $legal_name, string $legal_name_prefix)
    {
        return static::http()->post('companies', [
            'name' => $name,
            'legal_name' => $legal_name,
            'legal_name_prefix' => $legal_name_prefix
        ])->throw()->json();
    }

    public static function createCompanyBySpecial(int $id, string $name, string $legal_name, string $legal_name_prefix)
    {
        return static::http()->post('companies/special', [
            'id' => $id,
            'name' => $name,
            'legal_name' => $legal_name,
            'legal_name_prefix' => $legal_name_prefix
        ])->throw()->json();
    }

    public static function setStatusExist(int $id, string $company_module = null)
    {
        return static::http()->post('companies/' . $id . '/status-exists', [
            'company_module' => is_null($company_module) ? 'azo' : $company_module
        ])->throw()->json();
    }

    public static function setStatusNotActive(int $id, string $company_module = null)
    {
        return static::http()->post('companies/' . $id . '/status-not-active', [
            'company_module' => is_null($company_module) ? 'azo' : $company_module
        ])->throw()->json();
    }

    public static function createCompanyUser(int $user_id, int $company_id, string $phone, string $full_name)
    {
        return static::http()->post('companies/users', [
            'user_id' => $user_id,
            'company_id' => $company_id,
            'phone' => $phone,
            'full_name' => $full_name
        ])->throw()->json();
    }

    public static function createCompanyUserSpecial(int $id, int $user_id, int $company_id, string $phone, string $full_name)
    {
        return static::http()->post('companies/users/special', [
            'id' => $id,
            'user_id' => $user_id,
            'company_id' => $company_id,
            'phone' => $phone,
            'full_name' => $full_name
        ])->throw()->json();
    }

    public static function getCompanyUserByUserId($user_id)
    {
        return static::http()->get('companies/users/get-user-id', [
            'user_id' => $user_id
        ])->throw()->json();
    }

    public static function updateCompanyLegalNamePrefix($company_id, string $legal_name_prefix)
    {
        return static::http()->post("companies/$company_id" , [
           'legal_name_prefix' => $legal_name_prefix
        ])->throw()->json();
    }

    protected static function http()
    {
        return Http::baseUrl(config('local_services.service_prm.domain') . '/api/gate/')
            ->withHeaders([
                'Accept' => 'application/json',
                'Access-Token' => config('local_services.service_prm.service_token'),
                'Content-Type' => 'application/json'
            ]);
    }
}
