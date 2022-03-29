<?php

namespace App\Http\Controllers\ApiCallsGateway\ProblemCases;

use App\Enums\ProblemCaseTagTypeEnum;
use App\Filters\ProblemCaseTag\QProblemCaseTagFilter;
use App\Http\Controllers\Controller;
use App\Models\ProblemCaseTag;
use Illuminate\Http\Request;

class ProblemCaseTagsController extends Controller
{
    public function index(Request $request)
    {
        $tags = ProblemCaseTag::query()
            ->where('type_id', ProblemCaseTagTypeEnum::BEFORE())
            ->filterRequest($request, [
                QProblemCaseTagFilter::class,
            ]);

        return $tags->paginate($request->input('per_page') ?? 15);
    }
}
