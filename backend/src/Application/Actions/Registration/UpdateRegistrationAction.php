<?php


namespace App\Application\Actions\Registration;


use App\Domain\DomainException\DomainRecordNotFoundException;
use App\Domain\Registration\EventRegistration;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class UpdateRegistrationAction extends EventAction
{

    protected function action(): Response
    {
        $body = $this->request->getParsedBody();

        $registrationId = (int) $this->resolveArg('id');
        $registrationToUpdate = new EventRegistration($body['registration_id'], $body['event_id'], $body['user_id'],
            $body['name'], $body['time'], $body['waiting']);

        if ($this->eventRepository->eventExists($registrationToUpdate->getEventId())) {
            $udpatedRegistration = $this->eventRepository->updateRegistration($registrationId, $registrationToUpdate);

            return $this->respondWithData($udpatedRegistration);
        }else {
            return $this->response->withStatus(404);
        }
    }
}