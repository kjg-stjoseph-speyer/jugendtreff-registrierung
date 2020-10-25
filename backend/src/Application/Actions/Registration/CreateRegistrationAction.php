<?php


namespace App\Application\Actions\Registration;


use App\Domain\Registration\EventRegistration;
use Psr\Http\Message\ResponseInterface as Response;

class CreateRegistrationAction extends EventAction
{

    protected function action(): Response
    {
        $body = $this->request->getParsedBody();

        $registrationToInsert = new EventRegistration(-1, $body['event_id'], $body['user_id'],
            $body['name'], $body['email'], $body['time'], $body['waiting']);

        if ($this->eventRepository->eventExists($registrationToInsert->getEventId())) {
            $insertedRegistration = $this->eventRepository->createRegistration($registrationToInsert);

            return $this->respondWithData($insertedRegistration);
        }else {
            return $this->response->withStatus(404);
        }

    }
}