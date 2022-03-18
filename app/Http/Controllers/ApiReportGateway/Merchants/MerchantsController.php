<?php

declare(strict_types=1);

namespace App\Http\Controllers\ApiReportGateway\Merchants;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiReportGateway\Merchants\MerchantsResource;
use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MerchantsController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Merchant::query();

        return MerchantsResource::collection($query->paginate($request->query('per_page') ?? 15));
    }
}
