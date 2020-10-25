# Jugendtreff Registrierung Weboberfläche
Implementiert in Svelte mit svelte-materialify.

## Installation
Es wird `npm` benötigt.

Nach dem klonen des Repositories können mit folgendem Befehl die Abhängigkeiten installiert werden

```shell
npm i
```

Jetzt sollte die Anwendung konfiguriert werden (siehe Abschnitt Konfiguration) 

Danach kann die Anwendung mit 

```shell
npm run dev
```
in einem Entwicklungsserver gestartet werden.

## Konfiguration
Alle Einstellungen werden aus der Datei `src/config.js` geladen welche Standartmäßig nicht existiert. Im Repository ist eine Datei `src/config.js.example` vorhanden, die in  `src/config.js` umbenannt werden kann. Danach können dort die Einstellungen vorgenommen werden.

## Deployment
Um die Anwendung für den Produktionsbetrieb zu kompilieren wird folgender Befehl ausgeführt

```shell
npm run build
```
Dadurch wird ein `build` Verzeichnis im `public` Ordner erstellt. Danach wird der gesamte Inhalt des `public` Verzeichnisses auf den Webserver hochgeladen.