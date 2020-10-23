<?php


namespace App\Application\Actions\Registration;


use Psr\Http\Message\ResponseInterface as Response;

class ListEventsAction extends EventAction
{

    protected function action(): Response
    {
        $events = $this->eventRepository->getAllActiveEvents();
        return $this->respondWithData($events);
    }
}