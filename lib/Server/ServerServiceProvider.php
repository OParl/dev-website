<?php

namespace OParl\Server;

use App\Console\Commands\ServerPopulateCommand;
use App\Console\Commands\ServerResetCommand;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class ServerServiceProvider extends ServiceProvider
{
    public function boot(Router $router)
    {
        $this->loadViewsFrom(__DIR__.'/resources/views', 'server');
    }
}
