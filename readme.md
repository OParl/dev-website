Master: ![Travis CI Status: master](https://travis-ci.org/OParl/dev-website.svg?branch=master)<br />
Production: ![Travis CI Status: production](https://travis-ci.org/OParl/dev-website.svg?branch=production)

# OParl - Entwicklerplattform

Dies ist der Code zur [OParl-Entwicklerplattform](https://dev.oparl.org).
Die Plattform wird mit dem [Laravel Framework](https://laravel.com) entwickelt.  

## Mitentwickeln

### Systemanforderungen

- php > 5.6
- composer
- node > 6.0
- npm
- gulp

**Zusätzlich auf dem Server/Homestead VM/etc.**

- pandoc
- ghostscript
- imagemagick / convert

### Setup

```
git clone https://github.com/OParl/dev-website.git
composer install
cp .env.example .env
./artisan key:generate
./artisan deploy --fix-missing
./artisan setup
```

### GitHub Integration

Das Entwicklerportal ist sowohl über WebHooks als auch durch die API mit GitHub
integriert. Falls an den Integrationsschnittpunkten Änderungen vorgenommen werden
müssen, ist GitHub-Administrationszugriff auf ein Klon der
[OParl Spezifikation](OParl/spec) notwendig. Weiterhin empfiehlt sich beim Entwickeln
auf einer lokalen (nicht direkt aus dem Internet erreichbaren) Maschine die Verwendung
von [ngrok](https://ngrok.com/). Zur Integration sind eine GH Application und ein Webhook notwendig.

### Entwicklungsserver

Ein temporärer Server kann mit `php artisan serve` gestartet werden. Alternativ ist
die Verwendung von Laravel Homestead oder Valet als Entwicklungsserver zu empfehlen.

Bei Verwendung eines eigenen Webservers ist Document root der `public`-Ordner und die
Index-Datei ist `public/index.php`. Weitere Ordner sollten vom (Virtual-)Host aus nicht
zugänglich sein.

### Frontend

Ab gesehen davon basiert die Frontend Entwicklung im Code Management auf Laravel Elixir.
Zentrale Komponenten sind mit Vue.js realisiert, Wenn im Frontendcode gearbeitet wird,
empfiehlt es sich mit

```
gulp watch
```

dafür zu sorgen, dass der Code permanent für den Browser kompiliert wird. Es ist dagegen
nicht notwendig, diese generierten Dateien im Repository zu speichern, da sie während des
Deployments der Seite automatisch erstellt und aktualisiert werden.

## Lizenz

Dieses Programm steht unter den Bedingungen der [MIT-Lizenz](https://opensource.org/licenses/MIT)
zur Verfügung.
