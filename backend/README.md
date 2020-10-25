# Jugendtreff Registrierung REST API

Die REST API die Zugriff auch die in einer MySQL Datenbank gespeicherten Daten ermöglicht. Implementiert in PHP basierend auf dem Slim Framework 4.

## Einrichtung
Es wird `composer` benötigt um die Abhängigkeiten zu installieren. Um die Anwendung lokal auszuführen wird außerdem `php` mit den Erweiterungen `pdo_mysql` sowie `initl` benötigt.
Außerdem wird ein MySQL Server benötigt.

Nach dem klonen des Repositories folgenden Befehl ausführen um die Abhängigkeiten zu installieren

```shell
composer install
```

Jetzt sollte die Anwendung konfiguriert werden (siehe Abschnitt Konfiguration)


Danach kann die Anwendung mit 

```shell
composer start
```
im Entwicklungsmodes mit der lokalen `php` Installation gestartet werden. Der Standartmäßig auf Port 8000. Änderungen werden automatisch nach Speichern der Datei übernommen (ohne Neustart)

**Hinweis:** Bei einer solchen lokalen Ausführung funktioniert das Senden von E-Mails nicht

## Konfiguration
Alle Einstellungen werden aus der Datei `app/settings.php` geladen, welche Standartmäßig nicht existiert. Im Repository mitgeliefert wird eine `app/settings.php.example` welche in `app/settings.php` umbenannt werden kann. Danach können in dieser Datei Änderungen entsprechende des zu verwendenden Systems gemacht werden.

Für den Einsatz in einer Produktionsumgebung sollte `displayErrorDetails` auf false, sowie die Eigenschaft `path` des `loggers` auf den auskommentierten Teil gesetzt werden.

## Deployment
Um die Anwendung in einer Produktionsumgebung einzusetzen wird folgender Befehlt ausgeführt

```shell
composer install --no-dev --optimize-autoloader
```
Werden alle Dateien und Verzeichnisse auf den Webserver geladen.

## Datenstruktur
Die Daten werden nach folgendem Modell in der MySQL Datenbank gespeichert.  


Tabelle `events`

| Name | Typ | Bemerkung |
|-------------------------------|------------------|----------------------------------------------|
| id     				| int     		| Primärschlüssel, Auto-Increment  |
| time  				| bigint    	| Unix Zeitstempel in ms		 |
| in_charge 			| varchar(50) 	| Verantwortlicher 				 |
| max_participants 	| int 		| 							 |

Tabelle `registrations` 

| Name | Typ | Bemerkung |
|-------------------------------|------------------|-----------------------------------------------------------------------------|
| id     				| int     		| Primärschlüssel, Auto-Increment  					|
| event_id  			| int    		| Fremdschlüssel (`events`)		 					|
| user_id 				| int 		| 8 Stellig 				 						|
| name 				| varchar(25) 	| 							 					|
| email				| text		| 												|
| time				| bigint 		| Unix Zeitstempel in ms 							|
| waiting 				| bit(1)		| `true` wenn auf Warteliste, `false` wenn Teilnehmer 	|
| registration_time 		| bigint 		| Zeitpunkt der Registrierung, Unix Zeitstempel in ms 	|

In der Datei `database.sql` befinden sich SQL Befehle die diese Tabellen erstellen wenn das Skript importiert wird.