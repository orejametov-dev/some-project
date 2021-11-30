<?php

namespace App\Http\Controllers\ApiLawGateway;

use App\Http\Controllers\Controller;
use App\Services\User;
use Illuminate\Http\Request;

class ApiBaseController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = app(User::class);
            return $next($request);
        });
    }
}
