<?php

/* @var Illuminate\Routing\Router $router */
$router->get('/', ['uses' => 'RootController@index', 'as' => 'api.index']);

$router->group([
    'as'         => 'api.v1.',
    'domain'     => 'dev.'.config('app.url'),
    'prefix'     => 'api/v1/',
    'middleware' => ['api.format', 'track', 'bindings'],
], function () use ($router) {
    $router->resource('system', 'SystemController', ['only' => ['index', 'show']]);
    $router->resource('body', 'BodyController', ['only' => ['index', 'show']]);
    $router->resource('legislativeterm', 'LegislativeTermController', ['only' => ['index', 'show']]);
    $router->resource('agendaitem', 'AgendaItemController', ['only' => ['index', 'show']]);
    $router->resource('person', 'PersonController', ['only' => ['index', 'show']]);
    $router->resource('meeting', 'MeetingController', ['only' => ['index', 'show']]);
    $router->resource('organization', 'OrganizationController', ['only' => ['index', 'show']]);
    $router->resource('membership', 'MembershipController', ['only' => ['index', 'show']]);
    $router->resource('paper', 'PaperController', ['only' => ['index', 'show']]);
    $router->resource('consultation', 'ConsultationController', ['only' => ['index', 'show']]);
    $router->resource('location', 'LocationController', ['only' => ['index', 'show']]);
    $router->resource('file', 'FileController', ['only' => ['index', 'show']]);
});
