Master: [![Build Status](https://travis-ci.org/OParl/dev-website.svg?branch=master)](https://travis-ci.org/OParl/dev-website)
[![StyleCI](https://styleci.io/repos/37522193/shield?branch=master)](https://styleci.io/repos/37522193)
<br />
Production: [![Build Status](https://travis-ci.org/OParl/dev-website.svg?branch=production)](https://travis-ci.org/OParl/dev-website)

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
[OParl Spezifikation][repo:spec] notwendig. Weiterhin empfiehlt sich beim Entwickeln
auf einer lokalen (nicht direkt aus dem Internet erreichbaren) Maschine die Verwendung
von [ngrok][ngrok]. Zur Integration sind eine GH Application und ein Webhook notwendig.

### Entwicklungsserver

Ein temporärer Server kann mit `php artisan serve` gestartet werden. Alternativ ist
die Verwendung von Laravel Homestead oder Valet als Entwicklungsserver zu empfehlen.

Bei Verwendung eines eigenen Webservers ist Document root der `public`-Ordner und die
Index-Datei ist `public/index.php`. Weitere Ordner sollten vom (Virtual-)Host aus nicht
zugänglich sein.

### Frontend

Abgesehen davon basiert die Frontend Entwicklung im Code Management auf Laravel Elixir.
Zentrale Komponenten sind mit Vue.js realisiert, Wenn im Frontendcode gearbeitet wird,
empfiehlt es sich mit

```
gulp watch
```

dafür zu sorgen, dass der Code permanent für den Browser kompiliert wird. Es ist dagegen
nicht notwendig, diese generierten Dateien im Repository zu speichern, da sie während des
Deployments der Seite automatisch erstellt und aktualisiert werden.

## System Architecture

The OParl developer portal is comprised of several components. At the core, a Laravel 5
based web application acts as frontend and orchestrates updates to all the subcomponents.
Behind the scenes, the repositories of the [specification][repo:spec], [liboparl][repo:liboparl], 
[resources][repo:resources] and the [validator][repo:validator]. Addtionally, the server running
the main component also provides the full build environments for the specification and liboparl,
as well as the runtime environment for the validator.

### Webhooks

**From GitHub:**

- **OParl/spec**: will update specification downloads, schema assets and live version
  on push; acts according to tags and branches, as configured in `config/oparl.php`
- **OParl/validator**: will update the local validator repository on push; also adheres to config
- **OParl/resources**: will update the endpoints list in `endpoints.yml` on push
- **OParl/liboparl**: will update the local liboparl repository and schedule a liboparl build job;
  adheres to config
  
### Validator Workflow

TODO.

### Internationalization / Localization

TODO.

### Support versions and feature longevity

The main application follows Laravel framework updates with roughly one to two months drag. This is done
so that the release rush bugs will hopefully be fixed before the update. This policy was adopted after
framework updates broke not-yet-updated third party packages. The third party packages fetched from
[Packagist][packagist] and [npmjs][npmjs] are regularily checked for updates.

The development plaform itself is unversioned as it is under continuous development. The validator,
liboparl and the specification however follow the [Semantic Versioning][semver] specification. Additionally,
the reference and test API implementations of OParl have versioned endpoints at 
`https://dev.oparl.org/api/oparl/<version>/`. Check #46 for future plans on this.

Other provided API methods will eventually be documented using Swagger. See #47 on documentation status.

## License

This program is being provided under the terms of the [MIT License][mit]

[mit]: https://opensource.org/licenses/MIT
[ngrok]: https://ngrok.com
[npm]: https://npmjs.com
[packagist]: https://packagist.org
[repo:spec]: https://github.com/OParl/spec
[repo:liboparl]: https://github.com/OParl/liboparl
[repo:resources]: https://github.com/OParl/resources
[repo:validator]: https://github.com/OParl/validator
