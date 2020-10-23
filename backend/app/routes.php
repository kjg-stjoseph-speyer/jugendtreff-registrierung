<?php
declare(strict_types=1);

use App\Application\Actions\Registration\CreateEventAction;
use App\Application\Actions\Registration\CreateRegistrationAction;
use App\Application\Actions\Registration\DeleteEventAction;
use App\Application\Actions\Registration\DeleteRegistrationAction;
use App\Application\Actions\Registration\UpdateEventAction;
use App\Application\Actions\Registration\UpdateRegistrationAction;
use App\Application\Actions\Registration\ListEventsAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        return $response->withStatus(404);
    });

    $app->group('/events', function (Group $group) {
        // get all events
      $group->get('', ListEventsAction::class);

      // create new event
      $group->post('', CreateEventAction::class);

      // delete event
        $group->delete('/{id}', DeleteEventAction::class);

        // update event
        $group->put('/{id}', UpdateEventAction::class);
    });

    $app->group('/registrations', function (Group $group) {
       // create registration
        $group->post('', CreateRegistrationAction::class);

        // update registration
        $group->put('/{id}', UpdateRegistrationAction::class);

        // delete registration
        $group->delete('/{id}', DeleteRegistrationAction::class);
    });
};
