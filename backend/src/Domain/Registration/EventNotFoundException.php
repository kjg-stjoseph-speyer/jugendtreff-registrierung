<?php


namespace App\Domain\Registration;


use App\Domain\DomainException\DomainRecordNotFoundException;

class EventNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The event you requested does not exist.';
}