<?php

namespace OParl\Server;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use OParl\Server\Commands\PopulateCommand;
use OParl\Server\Commands\ResetCommand;

class ServerServiceProvider extends ServiceProvider
{
    public function boot(Router $router)
    {
        $router->group(['namespace' => 'OParl\Server\API\Controllers'], function ($router) {
            require __DIR__ . '/routes.php';
        });

        $this->loadViewsFrom(__DIR__ . '/resources/views', 'server');
    }

    public function register()
    {
        $this->commands(PopulateCommand::class);
        $this->commands(ResetCommand::class);
    }
}
