<?php

/* @var Illuminate\Routing\Router $router */
$router->group([
    'as'         => 'api.',
    'domain'     => 'dev.'.config('app.url'),
    'prefix'     => '/api/',
    'middleware' => ['track'],
], function () use ($router) {
    $router->get('/endpoints')
        ->name('endpoints.index')
        ->uses('EndpointApiController@index');
});
