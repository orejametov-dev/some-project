<?php


namespace App\Services\Tickets;

use GuzzleHttp\Client as HttpClient;


class TicketsService
{
    /** @var string $base_uri */
    private $base_uri;

    /** @var string $token */
    private $token;

    public function getTickets(int $subject_id, $params = [])
    {
        $request = $this->createRequest();
        $query = array_merge($params, [
            'subject_id' => $subject_id
        ]);
        $response = $request->get('tickets', [
            'query' => $query
        ]);

        return $this->decodeResponse($response);
    }

    public function getById(int $ticket_id)
    {
        $request = $this->createRequest();

        $response = $request->get("tickets/$ticket_id");

        return $this->decodeResponse($response);
    }

    public function getStatuses(int $subject_id)
    {
        $request = $this->createRequest();
        $response = $request->get("statuses/$subject_id");
        return $this->decodeResponse($response);
    }

    public function getTags(int $subject_id)
    {
        $request = $this->createRequest();

        $response = $request->get("tags/$subject_id");

        return $this->decodeResponse($response);
    }


    public function setComment(int $ticket_id, string $body)
    {
        $request = $this->createRequest();
        $response = $request->patch("tickets/$ticket_id/comment", [
            'json' => [
                'body' => $body
            ]
        ]);
        return $this->decodeResponse($response);
    }

    public function setDeadline(int $ticket_id, string $date)
    {
        $request = $this->createRequest();
        $body = [
            'date' => $date
        ];
        $response = $request->patch("tickets/$ticket_id/deadline", [
            'json' => $body
        ]);
        return $this->decodeResponse($response);
    }

    public function setStatus(int $ticket_id, int $status_id)
    {
        $request = $this->createRequest();
        $body = [
            'status_id' => $status_id
        ];

        $response = $request->patch("tickets/$ticket_id/status", [
            'json' => $body
        ]);

        return $this->decodeResponse($response);
    }

    public function assignUser(int $ticket_id, int $user_id, string $user_name, $status_id = null)
    {
        $request = $this->createRequest();
        $body = [
            'user_id' => $user_id,
            'user_name' => $user_name
        ];
        if (! is_null($status_id))
            $body['status_id'] = $status_id;

        $response = $request->patch("tickets/$ticket_id/assign", [
            'json' => $body
        ]);

        return $this->decodeResponse($response);
    }

    public function attachTags(int $ticket_id, array $tags_ids)
    {
        $request = $this->createRequest();
        $body = [
            'tags' => $tags_ids
        ];

        $response = $request->patch("tickets/$ticket_id/tags", [
            'json' => $body
        ]);

        return $this->decodeResponse($response);
    }

    private function createRequest()
    {
        return new HttpClient([
            'base_uri' => config('local_services.services_tickets.domain'),
            'headers' => [
                'Accept' => 'application/json',
                'Access-Token' => config('local_services.services_tickets.access_token')
            ]
        ]);
    }

    private function decodeResponse($response)
    {
        return json_decode($response->getBody()->getContents(), true);
    }
}
