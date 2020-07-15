# OParl - Entwicklerplattform

Welcome to the [OParl developer][platform] platform codebase. 

## Contributing

### System Requirements

- php >= 7.1
- composer
- node >= 8.0
- npm

Additionally, the requirements for running the [validator][repo:validator] and building the 
[specification][repo:spec] must be met on the system.

### Setup

```
git clone https://github.com/OParl/dev-website.git
composer install
cp .env.example .env
./artisan key:generate
./artisan deploy --fix-missing
./artisan setup
```

## System Architecture

The OParl developer portal is comprised of several components. At the core, a Laravel 5
based web application acts as frontend and orchestrates updates to all the subcomponents.
Behind the scenes, the repositories of the [specification][repo:spec], [liboparl][repo:liboparl], 
[resources][repo:resources] and the [validator][repo:validator]. Additionally, the server running
the main component also provides the full build environments for the specification and liboparl,
as well as the runtime environment for the validator.

### The .env-file

Eventhough most configuration lives inside the versioned `config/`-directory, some configuration
options must be set on a per host basis. These - for the most part - include all the security
related config options such as the application encryption key or the webhook secret for GitHub.

| Variable                 | Default Value           | Description                             |
| ------------------------ | ----------------------- | --------------------------------------- |
| APP_ENV                  | `production`            | Application environment                 |
| APP_DEBUG                | `false`                 | Toggles the debug mode                  |
| APP_KEY                  | `<none>`                | Application encryption key              |
| APP_URL                  | `http://localhost/`     |                                         |
| APP_LOG                  | `daily`                 | Controls the builtin logfile rotation   |
| ------------------------ | ----------------------- | --------------------------------------- |
| DB_DEFAULT               | `sqlite`                | Select db type for main db              |
| DB_HOST                  |                         | Host for mysql connection               |
| DB_DATABASE              |                         | Database name for mysql connection      |
| DB_USERNAME              |                         | User name for mysql connection          |
| DB_PASSWORD              |                         |                                         |
| ------------------------ | ----------------------- | --------------------------------------- |
| CACHE_DRIVER             | `file`                  | Storage driver for the app cache        |
| SESSION_DRIVER           | `file`                  | Storage driver for sessions             |
| QUEUE_DRIVER             | `file`                  | Queue driver for background tasks       |
| ------------------------ | ----------------------- | --------------------------------------- |
| PIWIK_URL                |                         | URL for Piwik tracking                  |
| PIWIK_SITE_ID            |                         | Piwik site id                           |
| ------------------------ | ----------------------- | --------------------------------------- |
| SLACK_ENABLED            | `false`                 | Globally toggle Slack integration       |
| SLACK_ENDPOINT           |                         |                                         |
| SLACK_CHANNEL_CI         | `#ci`                   |                                         |
| SLACK_CHANNEL_VALIDATION | `#feedback`             |                                         |
| ------------------------ | ----------------------- | --------------------------------------- |
| OPARL_BUILD_MODE         | `native`                | Specification build mode                |
| LIBOPARL_PREFIX          | `/usr/local`            | Install-dir for liboparl                |
| ------------------------ | ----------------------- | --------------------------------------- |
| GITHUB_WEBHOOK_SECRET    |                         | Secret for GitHub hook validation       |

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

Locales are managed via [Transifex][transifex:website] with all the other localizable products of OParl. 

### Support versions and feature longevity

The main application follows Laravel framework updates with roughly one to two months drag. This is done
so that the release rush bugs will hopefully be fixed before the update. This policy was adopted after
framework updates broke not-yet-updated third party packages. The third party packages fetched from
[Packagist][packagist] and [npmjs][npmjs] are regularily checked for updates.

The development plaform itself is unversioned as it is under continuous development. The validator,
liboparl and the specification however follow the [Semantic Versioning][semver] specification. 

## License

This program is being provided under the terms of the [MIT License][mit]


[mit]: https://opensource.org/licenses/MIT
[ngrok]: https://ngrok.com
[npm]: https://npmjs.com
[packagist]: https://packagist.org
[platform]: https://dev.oparl.org
[repo:spec]: https://github.com/OParl/spec
[repo:liboparl]: https://github.com/OParl/liboparl
[repo:resources]: https://github.com/OParl/resources
[repo:validator]: https://github.com/OParl/validator
[transifex:website]: https://transifex.com/OParl/dev-website
