<?php


namespace App\Application\Actions\Registration;


use App\Domain\Registration\Event;
use Psr\Http\Message\ResponseInterface as Response;

class CreateEventAction extends EventAction
{

    protected function action(): Response
    {
        $body = $this->request->getParsedBody();

        if (strcmp($body['key'], $this->settings['admin_key']) == 0) {
            $eventToInsert = new Event(-1, $body['data']['time'], $body['data']['in_charge'], $body['data']['max_participants']);

            $insertedEvent = $this->eventRepository->createEvent($eventToInsert);

            return $this->respondWithData($insertedEvent);
        }else {
            return $this->response->withStatus(401);
        }
    }
}