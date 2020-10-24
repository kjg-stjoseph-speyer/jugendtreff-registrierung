<?php


namespace App\Application\Actions\Registration;


use App\Domain\DomainException\DomainRecordNotFoundException;
use App\Domain\Registration\Event;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class UpdateEventAction extends EventAction
{

    protected function action(): Response
    {
        $body = $this->request->getParsedBody();

        if (strcmp($body['key'], $this->settings['admin_key']) == 0) {
            $userId = (int) $this->resolveArg('id');

            $eventToUpdate = new Event($body['data']['event_id'], $body['data']['time'], $body['data']['in_charge'], $body['data']['max_participants']);
            $updatedEvent = $this->eventRepository->updateEvent($userId, $eventToUpdate);

            return $this->respondWithData($updatedEvent);
        }else {
            return $this->response->withStatus(401);
        }
    }
}