<?php


namespace App\Http\Controllers\ApiGateway\ProblemCases;


use App\Http\Controllers\Controller;
use App\Modules\Merchants\Models\ProblemCase;

class ProblemCasesController extends Controller
{
    public function index()
    {
        $problemCases = ProblemCase::query();// need set relationship with tags
    }

    public function store()
    {
        
    }
}
