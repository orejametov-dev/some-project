<?php


namespace App\Http\Controllers\ApiGateway\ProblemCases;


use App\Http\Controllers\Controller;
use App\Modules\Merchants\Models\ProblemCaseTag;

class ProblemCaseTagsController extends Controller
{
    public function index()
    {
        return ProblemCaseTag::query()->orderBy('created_at', 'DESC')->get();
    }
}
