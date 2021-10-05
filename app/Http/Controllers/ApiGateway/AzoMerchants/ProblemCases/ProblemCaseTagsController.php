<?php


namespace App\Http\Controllers\ApiGateway\AzoMerchants\ProblemCases;


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

        if($request->has('object') and $request->query('object') == true) {
            return $tags->first();
        }

        if($request->has('paginate') and $request->query('paginate') == false) {
            return $tags->get();
        }

        return $tags->paginate($request->query('per_page') ?? 15);
    }
}
