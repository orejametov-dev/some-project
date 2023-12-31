<?php

namespace App\Http\Controllers\ApiLawGateway\ProblemCases;

use App\Enums\ProblemCaseTagTypeEnum;
use App\Filters\ProblemCaseTag\QProblemCaseTagFilter;
use App\Http\Controllers\Controller;
use App\Models\ProblemCaseTag;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProblemCaseTagsController extends Controller
{
    public function index(Request $request): JsonResource
    {
        $tags = ProblemCaseTag::query()
            ->where('type_id', ProblemCaseTagTypeEnum::BEFORE())
            ->filterRequest($request, [
                QProblemCaseTagFilter::class,
            ]);

        return JsonResource::collection($tags->paginate($request->input('per_page') ?? 15));
    }
}
