<?php


namespace App\Infrastructure\Persistence\Registration;


use App\Domain\Registration\Event;
use App\Domain\Registration\EventRegistration;
use App\Domain\Registration\EventRepository;
use PDO;
use PDOException;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class MySqlEventRepository implements EventRepository
{
    private $pdo;

    private $logger;
    private $settings;

    public function __construct(LoggerInterface $logger, ContainerInterface $c) {
        $this->logger = $logger;
        $this->settings = $c->get('settings');

        $host = $this->settings['mysql_host'];
        $db   = $this->settings['mysql_database'];
        $user = $this->settings['mysql_user'];
        $password = $this->settings['mysql_password'];
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $password, $options);
        }catch (PDOException $e) {
            $logger->error($e->getMessage());
        }
    }

    public function getAllActiveEvents(): array
    {
        // now + 24h
        $timeThreshold = round(microtime(true) * 1000) + 24*60*60*1000;

        $stmt = $this->pdo->prepare('SELECT * FROM events WHERE time > ?');
        $stmt->execute([$timeThreshold]);

        $events = [];
        while ($row = $stmt->fetch())
        {
            $e = $this->eventFromDbRow($row);

            $regStmt = $this->pdo->prepare('SELECT * FROM registrations WHERE event_id=?');
            $regStmt->execute([$e->getId()]);

            $regs = [];
            while ($regRow = $regStmt->fetch()) {
                array_push($regs, $this->registrationFromDbRow($regRow));
            }

            $e->setRegistrations($regs);

            array_push($events, $e);
        }

        return $events;
    }

    public function createEvent(Event $event): Event
    {
        $stmt = $this->pdo->prepare('INSERT INTO events (time, in_charge, max_participants) VALUES (?, ?, ?)');
        $stmt->execute([$event->getTime(), $event->getInCharge(), $event->getMaxParticipants()]);

        // get last inserted row
        $stmt = $this->pdo->query('SELECT * FROM events ORDER BY id DESC LIMIT 1');
        $row = $stmt->fetch();
        return $this->eventFromDbRow($row);
    }

    public function updateEvent(int $id, Event $updatedEvent): Event
    {
        $stmt = $this->pdo->prepare('UPDATE events SET time=?, in_charge=?, max_participants=? WHERE id=?');
        $stmt->execute([$updatedEvent->getTime(), $updatedEvent->getInCharge(), $updatedEvent->getMaxParticipants(), $id]);

        // get updated row
        $stmt = $this->pdo->prepare('SELECT * FROM events WHERE id=?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        $e = $this->eventFromDbRow($row);
        $e->setRegistrations($updatedEvent->getRegistrations());

        return $e;
    }

    public function deleteEvent(int $id): void
    {
        // delete all registrations
        $stmt = $this->pdo->prepare("DELETE FROM registrations WHERE event_id=?");
        $stmt->execute([$id]);

        // delete event
        $stmt = $this->pdo->prepare("DELETE FROM events WHERE id=?");
        $stmt->execute([$id]);
    }

    public function eventExists(int $id): bool
    {
        $stmt = $this->pdo->prepare('SELECT * FROM events WHERE id=?');
        $stmt->execute([$id]);
        return $stmt->fetchColumn();
    }

    public function createRegistration(EventRegistration $registration): EventRegistration
    {
        $registrationTime = round(microtime(true) * 1000);

        $stmt = $this->pdo->prepare('INSERT INTO registrations (event_id, user_id, name, time, waiting, registration_time) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([$registration->getEventId(), $registration->getUserId(), $registration->getName(),
            $registration->getTime(), $registration->isWaiting(), $registrationTime]);

        // get last inserted row
        $stmt = $this->pdo->query('SELECT * FROM registrations ORDER BY id DESC LIMIT 1');
        $row = $stmt->fetch();
        return $this->registrationFromDbRow($row);
    }

    public function updateRegistration(int $id, EventRegistration $registrationUpdate): EventRegistration
    {
        $stmt = $this->pdo->prepare('UPDATE registrations SET waiting=? WHERE id=?');
        $stmt->execute([$registrationUpdate->isWaiting(), $id]);

        // get updated row
        $stmt = $this->pdo->prepare('SELECT * FROM registrations WHERE id=?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        return $this->registrationFromDbRow($row);
    }

    public function deleteRegistration(int $id): void
    {
        // get event id
        $stmt = $this->pdo->prepare('SELECT * FROM registrations WHERE id=?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        $oldReg = $this->registrationFromDbRow($row);

        // delete registration
        $stmt = $this->pdo->prepare('DELETE FROM registrations WHERE id=?');
        $stmt->execute([$id]);

        if (!$oldReg->isWaiting()){
            // get next participant that is on waiting list for this event
            $stmt = $this->pdo->prepare('SELECT * FROM registrations WHERE event_id=? AND waiting=1 ORDER BY registration_time ASC LIMIT 1');
            $stmt->execute([$oldReg->getEventId()]);
            $newReg = $this->registrationFromDbRow($stmt->fetch());

            $newReg->setIsWaiting(false);

            $this->updateRegistration($newReg->getId(), $newReg);
        }
    }

    private function eventFromDbRow($row) : Event {
        return new Event($row['id'], $row['time'], $row['in_charge'], $row['max_participants']);
    }

    private function registrationFromDbRow($row) : EventRegistration {
        return new EventRegistration($row['id'], $row['event_id'], $row['user_id'], $row['name'], $row['time'],
            $row['waiting'], $row['registration_time']);
    }
}