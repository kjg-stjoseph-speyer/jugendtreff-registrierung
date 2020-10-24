<?php
declare(strict_types=1);

namespace App\Domain\Registration;

use JsonSerializable;

class EventRegistration implements JsonSerializable
{
    /**
     * @var int/null
     */
    private $id;

    /**
     * @var int
     */
    private $eventId;

    /**
     * @var int
     */
    private $userId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $time;

    /**
     * @var bool
     */
    private $isWaiting;

    /**
     * @var int
     */
    private $registrationTime;

    /**
     * EventRegistration constructor.
     * @param int|null  $id
     * @param int $eventId
     * @param int $userId
     * @param string $name
     * @param int $time
     * @param bool $isWaiting
     * @param int|null $registrationTime
     */
    public function __construct(?int $id, int $eventId, int $userId, string $name, int $time, bool $isWaiting, int $registrationTime = null)
    {
        $this->id = $id;
        $this->eventId = $eventId;
        $this->userId = $userId;
        $this->name = $name;
        $this->time = $time;
        $this->isWaiting = $isWaiting;

        if ($registrationTime == null) {
            $this->registrationTime = time();
        }else {
            $this->registrationTime = $registrationTime;
        }
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getEventId(): int
    {
        return $this->eventId;
    }

    /**
     * @param int $eventId
     */
    public function setEventId(int $eventId): void
    {
        $this->eventId = $eventId;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getTime(): int
    {
        return $this->time;
    }

    /**
     * @param int $time
     */
    public function setTime(int $time): void
    {
        $this->time = $time;
    }

    /**
     * @return bool
     */
    public function isWaiting(): bool
    {
        return $this->isWaiting;
    }

    /**
     * @param bool $isWaiting
     */
    public function setIsWaiting(bool $isWaiting): void
    {
        $this->isWaiting = $isWaiting;
    }

    /**
     * @return int
     */
    public function getRegistrationTime(): int
    {
        return $this->registrationTime;
    }

    /**
     * @param int $registrationTime
     */
    public function setRegistrationTime(int $registrationTime): void
    {
        $this->registrationTime = $registrationTime;
    }

    public function jsonSerialize()
    {
        return [
            'registrationId' => $this->id,
            'event_id' => $this->eventId,
            'user_id' => $this->userId,
            'name' => $this->name,
            'time' => $this->time,
            'registration_time' => $this->registrationTime,
            'waiting' => $this->isWaiting
        ];
    }
}