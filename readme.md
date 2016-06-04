[![Build Status](https://travis-ci.org/OParl/spec-website.svg?branch=master)](https://travis-ci.org/OParl/spec-website)

# OParl - Spec Webseite

Hier wird die Entwicklerplattform für [OParl.org](https://oparl.org) entwickelt.

## Setup mit Homestead
Dieses Programm wird mit Hilfe des [Laravel 5.1](laravel/laravel) Frameworks in PHP 5.5 entwickelt.
Allgemeine Hinweise zum Aufsetzen einer lokalen Entwicklungsumgebung für Laravelanwendungen findet 
sich in der [Dokumentation](http://laravel.com/docs/5.1/homestead). 

Die Umgebungsvariablen in `.env.example` sind die für 
Homestead funktionierenden Defaultwerte, daher
reicht es, diese  einfach in eine `.env` zu kopieren. 
Einzig die Variable `APP_KEY` **muss** modifiziert werden.
Dieser kann manuell ein 32-zeichiger Schlüssel zugewiesen werden.
Alternativ generiert das `php artisan app:key`-Kommando einen
zufälligen Schlüssel.

## Manuelles Setup

Vorrausgesetzt wird ein System mit PHP >= 5.6 sowie composer, npm und gulp.

Die `.env.example` muss in `.env` kopiert werden und die Standartwerte
müssen durch die korrekten Werte ersetzt werden.

```
composer install
php artisan setup
php artisan deploy
php artisan specification:live
```

Ein temporärer Server kann mit `php artisan serve` gestartet werden.

Bei Verwendung eines eigenen Webservers ist Document root der `public`-Ordner und die Index-Datei ist `public/index.php`.

## Lizenz

Dieses Programm steht unter den Bedingungen der
[MIT-Lizenz](https://opensource.org/licenses/MIT) zur
Verfügung.

