<?php


namespace App\Application\Actions\Registration;


use App\Domain\DomainException\DomainRecordNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class DeleteEventAction extends EventAction
{

    protected function action(): Response
    {
        $body = $this->request->getParsedBody();

        // TODO: load key from settings
        if (strcmp($body['key'], "123456") == 0) {
            $userId = (int) $this->resolveArg('id');

            $this->eventRepository->deleteEvent($userId);
            return $this->response->withStatus(200);
        }else {
            return $this->response->withStatus(401);
        }
    }
}