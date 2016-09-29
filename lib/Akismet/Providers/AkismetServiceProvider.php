<?php

namespace EFrane\Akismet\Providers;

use EFrane\Akismet\Akismet;
use Illuminate\Support\ServiceProvider;

class AkismetServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bindShared('EFrane\Akismet\Akismet', function () {
            return new Akismet(config('services.akismet.key'), config('app.url'), config('app.debug'));
        });
    }
}
