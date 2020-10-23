<?php
declare(strict_types=1);

namespace App\Domain\Registration;


use App\Domain\DomainException\DomainRecordNotFoundException;

class RegistrationNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The registration you requested does not exist.';
}