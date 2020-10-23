<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Registration;


use App\Domain\Registration\Event;
use App\Domain\Registration\EventRegistration;
use App\Domain\Registration\EventRepository;

class InMemoryEventRepository implements EventRepository
{
    /**
     * @var Event[]
     */
    private $events;

    /**
     *
     * @param array|null $events
     */
    public function __construct(array $events = null)
    {
        $this->events = $events ?? [
                1 => new Event(1, 1603472400000, 'Simon', 15),
                2 => new Event(2, 1603823400000, 'Simon', 10),
                3 => new Event(3, 1604088000000, 'Simon', 15),
            ];
    }

    public function getAllActiveEvents(): array
    {
        return array_values($this->events);
    }

    public function createEvent(Event $event): Event
    {
        $event->setId(sizeof($this->events) + 1);
        array_push($this->events, $event);
        return $event;
    }

    public function updateEvent(int $id, Event $updatedEvent): Event
    {
        $this->events = array_replace($this->events, [$id => $updatedEvent]);
        return $updatedEvent;
    }

    public function deleteEvent(int $id): void
    {
        unset($this->events[$id]);
    }

    public function createRegistration(EventRegistration $registration): EventRegistration
    {
        $event = $this->events[$registration->getEventId()];
        $registration->setId(sizeof($event->getRegistrations()) + 1);
        $array = $event->getRegistrations();
        array_push($array, $registration);
        $event->setRegistrations($array);

        $this->events = array_replace($this->events, [$event->getId() => $event]);

        return $registration;
    }

    public function updateRegistration(int $id, EventRegistration $registrationUpdate): EventRegistration
    {
        $event = $this->events[$registrationUpdate->getEventId()];
        $updatedRegistrations = array_replace($event->getRegistrations(), [$id => $registrationUpdate]);
        $event->setRegistrations($updatedRegistrations);

        $this->events = array_replace($this->events, [$event->getId() => $event]);

        return $registrationUpdate;
    }

    public function deleteRegistration(int $id): void
    {
        // Not implemented
    }

    public function eventExists(int $id): bool
    {
        return true;
    }
}