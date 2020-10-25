<?php


namespace App\Application\Actions\Registration;


use App\Domain\DomainException\DomainRecordNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class CheckAuthenticationAction extends EventAction
{

    protected function action(): Response
    {
        $body = $this->request->getParsedBody();
        if (strcmp($body['key'], $this->settings['admin_key']) == 0) {
            // valid
            return $this->respondWithData(['valid' => true]);
        }else {
            // invalid
            return $this->respondWithData(['valid' => false]);
        }
    }
}