<?php

namespace App\Http\Controllers\ApiGateway\Tickets;

use App\Http\Controllers\Controller;
use App\Modules\Merchants\Models\Merchant;
use App\Services\Tickets\TicketsService;
use App\Services\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class TicketsController extends Controller
{
    /** @var TicketsService $ticketsService */
    private $ticketsService;

    private $subject_id;

    public function __construct(TicketsService $ticketsService)
    {
        $this->ticketsService = $ticketsService;
        $this->subject_id = config('local_services.services_tickets.problem_subject_id');
    }

    public function index(Request $request)
    {
        $tickets = $this->ticketsService->getTickets($this->subject_id, $request->query());
        return response()->json($tickets);
    }

    public function show(int $id)
    {
        $ticket = $this->ticketsService->getById($id);

        return response()->json($ticket);
    }

    public function comment(Request $request, int $id)
    {
        $request->validate([
            'body' => 'required|string|max:500'
        ]);

        $ticket = $this->ticketsService->setComment($id, $request->input('body'));

        return response()->json($ticket);
    }

    public function deadline(Request $request, int $id)
    {
        $request->validate([
            'date' => 'required|date_format:Y-m-d'
        ]);

        $ticket = $this->ticketsService->setDeadline($id, $request->input('date'));

        return response()->json($ticket);
    }

    public function assign(int $id)
    {
        $user_id = app(User::class)->id;
        $user_name = app(User::class)->name;
        $status_id = 2;

        $ticket = $this->ticketsService->assignUser($id, $user_id, $user_name, $status_id);

        return response()->json($ticket);
    }

    public function tags(Request $request, int $id)
    {
        $request->validate([
            'tags' => 'required|array'
        ]);

        $tags = $request->input('tags');

        $ticket = $this->ticketsService->attachTags($id, $tags);

        return response()->json($ticket);
    }

    public function finish(int $id)
    {
        $statuses = $this->ticketsService->getStatuses($this->subject_id);
        $finish_status = Arr::first(array_filter($statuses, function ($status) { return $status['name'] == 'Завершен'; }));
        if (is_null($finish_status))
            return response()->json(['message' => 'Соответствующий статус не найден'], 400);
        return $this->ticketsService->setStatus($id, $finish_status['id']);
    }

    public function reject(int $id)
    {
        $statuses = $this->ticketsService->getStatuses($this->subject_id);
        $status = Arr::first(array_filter($statuses, function ($status) { return $status['name'] == 'Рассматривается'; }));
        if (is_null($status))
            return response()->json(['message' => 'Соответствующий статус не найден'], 400);
        return $this->ticketsService->setStatus($id, $status['id']);
    }
}
