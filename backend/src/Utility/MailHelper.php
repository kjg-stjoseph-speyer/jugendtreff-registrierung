<?php
declare(strict_types=1);

namespace App\Utility;


use App\Domain\Registration\Event;
use App\Domain\Registration\EventRegistration;

class MailHelper
{
    public static function sendMail(string $to, string $from, string $subject, string $content) {
        $header = 'From: ' . $from;
        mail($to, $subject, $content, $header);
    }

    public static function sendWaitingChangedMail(string $sender, EventRegistration $registration, Event $event) {
        if (strcmp($registration->getEmail(), "") == 0) {
            // no email specified
            return;
        }

        $subj = "Teilnahme an Jugendtreff am " . gmdate("d.M.", $event->getTime());

        $state = $registration->isWaiting() ? "Warteliste" : "Teilnehmerliste";

        $content = "Hallo " . $registration->getName() . ", \r\n" . "Du wurde für den Jugendtreff am " . gmdate("d.M.", $event->getTime()) .
            " auf die " . $state . " gesetzt.";

        self::sendMail($registration->getEmail(), $sender, $subj, $content);
    }

    public static function sendEventDeleteMail(string $sender, Event $event) {
        $subj = "Jugendtreff am " . gmdate("d.M.", $event->getTime()) . " abgesagt";

        foreach ($event->getRegistrations() as $registration) {
            if (strcmp($registration->getEmail(), "") != 0) {
                // has email specified
                $content = "Hallo " . $registration->getName() . ", \r\n" . "Der Jugendtreff am " . gmdate("d.M.", $event->getTime()) . " wurde leider abgesagt.";

                self::sendMail($registration->getEmail(), $sender, $subj, $content);
            }
        }
    }

    public static function sendEventUpdateMail(string $sender, Event $old, Event $new) {
        $subj = "Änderungen an Jugendtreff am " . gmdate("d.M.", $old->getTime());

        foreach ($old->getRegistrations() as $registration) {
            if (strcmp($registration->getEmail(), "") != 0) {
                // has email specified
                $content = "Hallo " . $registration->getName() . ", \r\n" . "Es wurden Änderungen am Termin vorgenommen\r\n"
                    . "Ursprünglich: \r\n"
                        . "Zeit: " . gmdate("d.M., H:i", $old->getTime()) . "Uhr \r\n"
                        . "Verantwortlicher: " . $old->getInCharge() . "\r\n"
                        . "Max. Teilnehmer: " . $old->getMaxParticipants() . "\r\n"
                    . "\r\n"
                    . "Jetzt: \r\n"
                        . "Zeit: " . gmdate("d.M., H:i", $new->getTime()) . "Uhr \r\n"
                        . "Verantwortlicher: " . $new->getInCharge() . "\r\n"
                        . "Max. Teilnehmer: " . $new->getMaxParticipants() . "\r\n";

                self::sendMail($registration->getEmail(), $sender, $subj, $content);
            }
        }
    }
}