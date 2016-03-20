<?php

namespace OParl\Server;

use Illuminate\Support\ServiceProvider;

class ServerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (!$this->app->routesAreCached()) {
            require __DIR__ . '/routes.php';
        }

        $this->loadViewsFrom(__DIR__ . '/resources/views', 'server');
    }

    public function register()
    {
    }
}
