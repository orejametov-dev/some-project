<?php


namespace App\Services;


class User
{
    public $id;
    public $name;
    public $phone;
    public $created_at;
    public $avatar_link;
    public $prm_admin;

    public function __construct(array $requestData)
    {
        $this->id = $requestData['id'];
        $this->name = $requestData['name'];
        $this->phone = $requestData['phone'];
        $this->avatar_link = $requestData['avatar_link'];
        $this->prm_admin = $requestData['prm_admin'];
        $this->created_at = $requestData['created_at'];
    }
}
