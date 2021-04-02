<?php


namespace App\Http\Controllers\ApiGateway\App;


use App\Http\Controllers\Controller;
use App\Modules\Merchants\Models\Request;
use App\Services\Tickets\TicketsService;
use Illuminate\Support\Facades\Cache;

class CountersController extends Controller
{
    /** @var TicketsService $ticketsService */
    private $ticketsService;

    public function __construct(TicketsService $ticketsService)
    {
        $this->ticketsService = $ticketsService;
    }

    public function merchantRequests()
    {
        $count = Cache::remember('prm_merchant_requests', 60, function () {
            return Request::new()
                ->count();
        });

        return response()->json(compact('count'));
    }

}
