<?php


namespace App\Http\Controllers\ApiGateway\ProblemCases;


use App\Http\Controllers\Controller;
use App\Modules\Merchants\Models\ProblemCaseTag;
use Illuminate\Http\Request;

class ProblemCaseTagsController extends Controller
{
    public function index(Request $request)
    {
        $tags = ProblemCaseTag::query()
            ->filterRequests($request)
            ->orderBy('created_at', 'DESC');

        if($request->query('object') == true) {
            $tags->first();
        }

        if($request->query('paginate') == false) {
            $tags->get();
        }

        return $tags->paginate($request->query('per_page') ?? 15);
    }
}
