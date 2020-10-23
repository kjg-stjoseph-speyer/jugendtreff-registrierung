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
     * EventRegistration constructor.
     * @param int|null  $id
     * @param int $eventId
     * @param int $userId
     * @param string $name
     * @param int $time
     * @param bool $isWaiting
     */
    public function __construct(?int $id, int $eventId, int $userId, string $name, int $time, bool $isWaiting)
    {
        $this->id = $id;
        $this->eventId = $eventId;
        $this->userId = $userId;
        $this->name = $name;
        $this->time = $time;
        $this->isWaiting = $isWaiting;
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

    public function jsonSerialize()
    {
        return [
            'registrationId' => $this->id,
            'event_id' => $this->eventId,
            'user_id' => $this->userId,
            'name' => $this->name,
            'time' => $this->time,
            'waiting' => $this->isWaiting
        ];
    }
}