<?php

namespace OParl\Website\API;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class ApiServiceProvider extends ServiceProvider
{
    public function boot(Router $router)
    {
        $router->group(['namespace' => 'OParl\Server\API\Controllers'], function ($router) {
            require __DIR__.'/routes.php';
        });
    }
}
