<?php
declare(strict_types=1);

namespace App\Application\Actions\Registration;

use App\Application\Actions\Action;
use App\Domain\Registration\EventRepository;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

abstract class EventAction extends Action
{
    /**
     * @var EventRepository
     */
    protected $eventRepository;

    /**
     * @var array
     */
    protected $settings;

    /**
     * @param LoggerInterface $logger
     * @param EventRepository  $eventRepository
     * @param ContainerInterface $c
     */
    public function __construct(LoggerInterface $logger, EventRepository $eventRepository, ContainerInterface $c)
    {
        parent::__construct($logger);
        $this->eventRepository = $eventRepository;
        $this->settings = $c->get('settings');
    }
}