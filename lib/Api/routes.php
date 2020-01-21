<?php

/* @var Illuminate\Routing\Router $router */
$router->group([
    'as'         => 'api.',
    'domain'     => 'dev.'.config('app.url'),
    'prefix'     => '/api/',
    'middleware' => ['track'],
], function () use ($router) {
    $router->get('/')
        ->name('index')
        ->uses('ApiController@index');

    $router->get('/openapi.json')
        ->name('openApi')
        ->uses('ApiController@openApiJson');

    $router->get('/endpoints')
        ->name('endpoints.index')
        ->uses('EndpointApiController@index');
});
