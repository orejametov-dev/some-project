<?php

namespace App\Http\Controllers\ApiGateway\Tickets;

use App\Http\Controllers\ApiGateway\ApiBaseController;
use App\Services\Tickets\TicketsService;

class TagsController extends ApiBaseController
{
    /** @var TicketsService $ticketsService */
    private $ticketsService;

    public function __construct(TicketsService $ticketsService)
    {
        parent::__construct();
        $this->ticketsService = $ticketsService;
    }

    public function index()
    {
        $tickets_problem_subject_id = config('local_services.services_tickets.problem_subject_id');
        $ticket_tags = $this->ticketsService->getTags($tickets_problem_subject_id);

        return response()->json($ticket_tags);
    }
}
