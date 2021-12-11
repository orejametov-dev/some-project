<?php

namespace App\Modules\Merchants\DTO\ProblemCases;

use Carbon\Carbon;
use Illuminate\Http\Request;

class ProblemCaseDTO
{


    public int $merchant_id;
    public int $store_id;
    public int $client_id;
    public string $search_index;

    public string $client_name;
    public string $client_surname;
    public string $client_patronymic;
    public string $phone;


    public ?array $application_items;
    public int $created_by_id;
    public string $created_by_name;
    public string $created_from_name;
    public int $post_or_pre_created_by_id;
    public string $post_or_pre_created_by_name;
    public ?string $description;

    public ?int $credit_number;
    public $credit_contract_date;
    public ?int $application_id;
    public $application_created_at;

    public function fromConstruct(
        int     $merchant_id,
        int     $store_id,
        int     $client_id,
        string  $search_index,
        string  $client_name,
        string  $client_surname,
        string  $client_patronymic,
        string  $phone,
        ?array  $application_items,
        int     $created_by_id,
        string  $created_by_name,
        string  $created_from_name,
        int     $post_or_pre_created_by_id,
        string  $post_or_pre_created_by_name,
        ?string $description,
        ?int    $credit_number,
                $credit_contract_date,
        ?int    $application_id,
                $application_created_at,


    )
    {
        $this->merchant_id = $merchant_id;
        $this->store_id = $store_id;
        $this->client_id = $client_id;
        $this->search_index = $search_index;
        $this->client_name = $client_name;
        $this->client_surname = $client_surname;
        $this->client_patronymic = $client_patronymic;
        $this->phone = $phone;
        $this->application_items = $application_items;
        $this->created_by_id = $created_by_id;
        $this->created_by_name = $created_by_name;
        $this->created_from_name = $created_from_name;
        $this->post_or_pre_created_by_id = $post_or_pre_created_by_id;
        $this->post_or_pre_created_by_name = $post_or_pre_created_by_name;
        $this->description = $description;
        $this->credit_number = $credit_number;
        $this->credit_contract_date = $credit_contract_date;
        $this->application_id = $application_id;
        $this->application_created_at = $application_created_at;

        return $this;
    }

    public function fromProblemCaseRequest(Request $request, array $data, string $create_from_name, $user)
    {
        $this->credit_number = $request->input('credit_number');
        $this->credit_contract_date = $data['contract_date'];
        $this->application_id = $request->input('application_id');
        $this->application_created_at = Carbon::parse($data['created_at'])->format('Y-m-d');

        $this->merchant_id = $data['merchant_id'];
        $this->store_id = $data['store_id'];
        $this->client_id = $data['client']['id'];
        $this->search_index = $data['client']['name']
            . ' ' . $data['client']['surname']
            . ' ' . $data['client']['patronymic']
            . ' ' . $data['client']['phone'];

        $this->client_name = $data['client']['name'];
        $this->client_surname = $data['client']['surname'];
        $this->client_patronymic = $data['client']['patronymic'];
        $this->phone = $data['client']['phone'];

        $this->application_items = $data['application_items'];

        $this->post_or_pre_created_by_id = $data['merchant_engaged_by']['id'];
        $this->post_or_pre_created_by_name = $data['merchant_engaged_by']['name'];

        $this->created_from_name = $create_from_name;
        $this->created_by_id = $user->id;
        $this->created_by_name = $user->name;
        $this->description = $request->input('description');

        return $this;
    }
}
