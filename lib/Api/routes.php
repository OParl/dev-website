<?php

/* @var Illuminate\Routing\Router $router */
$router->group([
    'as'         => 'api.v1',
    'domain'     => 'dev.'.config('app.url'),
    'prefix'     => '/api/v1',
    'middleware' => ['track'],
], function () use ($router) {
    $router->resource('endpoint', 'EndpointController', ['only' => ['index', 'show']]);
});
