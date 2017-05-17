<?php

/* @var Illuminate\Routing\Router $router */

$router->get('/api/oparl/v1/', ['uses' => 'RootController@index', 'as' => 'api.oparl.index']);

$router->group([
    'as'         => 'api.oparl.v1.',
    'domain'     => 'dev.'.config('app.url'),
    'prefix'     => 'api/oparl/v1/',
    'middleware' => ['track', 'bindings'],
], function () use ($router) {
    $apiOnlyIndexAndShow = ['only' => ['index', 'show']];

    $router->resource('system', 'SystemController', $apiOnlyIndexAndShow);
    $router->resource('body', 'BodyController', $apiOnlyIndexAndShow);
    $router->resource('legislativeterm', 'LegislativeTermController', $apiOnlyIndexAndShow);
    $router->resource('agendaitem', 'AgendaItemController', $apiOnlyIndexAndShow);
    $router->resource('person', 'PersonController', $apiOnlyIndexAndShow);
    $router->resource('meeting', 'MeetingController', $apiOnlyIndexAndShow);
    $router->resource('organization', 'OrganizationController', $apiOnlyIndexAndShow);
    $router->resource('membership', 'MembershipController', $apiOnlyIndexAndShow);
    $router->resource('paper', 'PaperController', $apiOnlyIndexAndShow);
    $router->resource('consultation', 'ConsultationController', $apiOnlyIndexAndShow);
    $router->resource('location', 'LocationController', $apiOnlyIndexAndShow);
    $router->resource('file', 'FileController', $apiOnlyIndexAndShow);
});
