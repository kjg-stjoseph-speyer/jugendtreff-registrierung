<?php
declare(strict_types=1);

namespace App\Domain\Registration;


interface EventRepository
{
    /**
     * @return Event[]
     */
    public function getAllActiveEvents(): array;

    /**
     * @param $event Event
     * @return Event
     */
    public function createEvent(Event $event): Event;

    /**
     * @param int $id
     * @param Event $updatedEvent
     * @return Event
     */
    public function updateEvent(int $id, Event $updatedEvent): Event;

    /**
     * @param $id int
     */
    public function deleteEvent(int $id): void;

    /**
     * @param int $id
     * @return bool
     */
    public function eventExists(int $id): bool;

    /**
     * @param EventRegistration $registration
     * @return EventRegistration
     */
    public function createRegistration(EventRegistration $registration): EventRegistration;

    /**
     * @param int $id
     * @param EventRegistration $registrationUpdate
     * @return EventRegistration
     */
    public function updateRegistration(int $id, EventRegistration $registrationUpdate): EventRegistration;

    /**
     * @param int $id
     */
    public function deleteRegistration(int $id): void;
}