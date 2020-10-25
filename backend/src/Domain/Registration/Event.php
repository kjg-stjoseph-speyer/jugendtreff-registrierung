<?php
declare(strict_types=1);

namespace App\Domain\Registration;

use JsonSerializable;

class Event implements JsonSerializable
{
    /**
     * @var int/null
     */
    private $id;

    /**
     * @var int
     */
    private $time;

    /**
     * @var string
     */
    private $inCharge;

    /**
     * @var int
     */
    private $maxParticipants;

    /**
     * @var EventRegistration[]
     */
    private $registrations;

    /**
     * Event constructor.
     * @param int $id
     * @param int $time
     * @param string $inCharge
     * @param int $maxParticipants
     */
    public function __construct(?int $id, int $time, string $inCharge, int $maxParticipants)
    {
        $this->id = $id;
        $this->time = $time;
        $this->inCharge = $inCharge;
        $this->maxParticipants = $maxParticipants;
        $this->registrations = [];
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
     * @return string
     */
    public function getInCharge(): string
    {
        return $this->inCharge;
    }

    /**
     * @param string $inCharge
     */
    public function setInCharge(string $inCharge): void
    {
        $this->inCharge = $inCharge;
    }

    /**
     * @return int
     */
    public function getMaxParticipants(): int
    {
        return $this->maxParticipants;
    }

    /**
     * @param int $maxParticipants
     */
    public function setMaxParticipants(int $maxParticipants): void
    {
        $this->maxParticipants = $maxParticipants;
    }

    /**
     * @return EventRegistration[]
     */
    public function getRegistrations(): array
    {
        return $this->registrations;
    }

    /**
     * @param EventRegistration[] $registrations
     */
    public function setRegistrations(array $registrations): void
    {
        $this->registrations = $registrations;
    }


    public function jsonSerialize()
    {
        return [
            'event_id' => $this->id,
            'time' => $this->time,
            'in_charge' => $this->inCharge,
            'max_participants' => $this->maxParticipants,
            'registrations' => $this->registrations
        ];
    }

    public function dump() {
        return "[id=" . $this->id . ", time=" . $this->time . ", inCharge='" . $this->inCharge . "', maxParticipants=" . $this->maxParticipants . "]";
    }
}