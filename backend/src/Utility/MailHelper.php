<?php
declare(strict_types=1);

namespace App\Utility;


use App\Domain\Registration\Event;
use App\Domain\Registration\EventRegistration;

class MailHelper
{
    public static function sendMail(string $to, string $from, string $subject, string $content) {
        $header = "From: " . $from . "\r\n" . "Content-Type: text/plain; charset=UTF-8";
        mail($to, $subject, $content, $header);
    }

    public static function sendWaitingChangedMail(string $sender, EventRegistration $registration, Event $event) {
        if (strcmp($registration->getEmail(), "") == 0) {
            // no email specified
            return;
        }

        setlocale(LC_TIME,"de_DE");
        $subj = "Teilnahme an Jugendtreff am " . strftime("%a, %d.%b.", $event->getTime() / 1000 + 60*60);

        $state = $registration->isWaiting() ? "Warteliste" : "Teilnehmerliste";

        $content = "Hallo " . $registration->getName() . ", \r\n" . "du wurdest für den Jugendtreff am "
            . strftime("%a, %d.%b. %R Uhr", $event->getTime() / 1000 + 60*60) .
            " auf die " . $state . " gesetzt.";

        self::sendMail($registration->getEmail(), $sender, $subj, $content);
    }

    public static function sendEventDeleteMail(string $sender, Event $event) {
        setlocale(LC_TIME,"de_DE");
        $subj = "Jugendtreff am " . strftime("%a, %d.%b. %R Uhr", $event->getTime() / 1000 + 60*60) . " abgesagt";

        foreach ($event->getRegistrations() as $registration) {
            if (strcmp($registration->getEmail(), "") != 0) {
                // has email specified
                $content = "Hallo " . $registration->getName() . ", \r\n" . "der Jugendtreff am "
                    . strftime("%a, %d.%b. %R Uhr", $event->getTime() / 1000 + 60*60)
                    . " wurde leider abgesagt.";

                self::sendMail($registration->getEmail(), $sender, $subj, $content);
            }
        }
    }

    public static function sendEventUpdateMail(string $sender, Event $old, Event $new) {
        setlocale(LC_TIME,"de_DE");
        $subj = "Änderungen an Jugendtreff am " . strftime("%a, %d.%b. %R Uhr", $old->getTime() / 1000 + 60*60);

        foreach ($old->getRegistrations() as $registration) {
            if (strcmp($registration->getEmail(), "") != 0) {
                // has email specified
                $content = "Hallo " . $registration->getName() . ", \r\n" . "es wurden Änderungen am Termin vorgenommen\r\n \r\n"
                    . "Ursprünglich: \r\n"
                        . "Zeit: " . strftime("%a, %d.%b. %R Uhr", $old->getTime() / 1000 + 60*60) . "\r\n"
                        . "Verantwortlicher: " . $old->getInCharge() . "\r\n"
                        . "Max. Teilnehmer: " . $old->getMaxParticipants() . "\r\n"
                    . "\r\n"
                    . "Jetzt: \r\n"
                        . "Zeit: " . strftime("%a, %d.%b. %R Uhr", $new->getTime() / 1000 + 60*60) . "\r\n"
                        . "Verantwortlicher: " . $new->getInCharge() . "\r\n"
                        . "Max. Teilnehmer: " . $new->getMaxParticipants() . "\r\n";

                self::sendMail($registration->getEmail(), $sender, $subj, $content);
            }
        }
    }
}