<?php


namespace App\Application\Actions\Registration;


use Psr\Http\Message\ResponseInterface as Response;

class DeleteRegistrationAction extends EventAction
{

    protected function action(): Response
    {
        $registrationId = (int) $this->resolveArg('id');
        $this->eventRepository->deleteRegistration($registrationId);

        return $this->response->withStatus(200);
    }
}