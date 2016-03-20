<?php

Route::group(['prefix' => 'api/'], function () {
    Route::get('/', [
        'uses' => 'OParl\Server\API\Controllers\RootController@index',
        'as'   => 'api.index'
    ]);

    Route::resource('system',
        'OParl\Server\API\Controllers\SystemController',
        ['only' => 'index']
    );

    Route::resource('body',
        'OParl\Server\API\Controllers\BodyController',
        ['only' => ['index', 'show']]
    );

    Route::resource('legislativeterm',
        'OParl\Server\API\Controllers\LegislativeTermController',
        ['only' => ['index', 'show']]
    );

    Route::resource('agendaitem',
        'OParl\Server\API\Controllers\AgendaItemController',
        ['only' => ['index', 'show']]
    );

    Route::resource('person',
        'OParl\Server\API\Controllers\PersonController',
        ['only' => ['index', 'show']]
    );

    Route::resource('meeting',
        'OParl\Server\API\Controllers\MeetingController',
        ['only' => ['index', 'show']]
    );

    Route::resource('organization',
        'OParl\Server\API\Controllers\OrganizationController',
        ['only' => ['index', 'show']]
    );

    Route::resource('membership',
        'OParl\Server\API\Controllers\MembershipController',
        ['only' => ['index', 'show']]
    );

    Route::resource('paper',
        'OParl\Server\API\Controllers\PaperController',
        ['only' => ['index', 'show']]
    );

    Route::resource('consultation',
        'OParl\Server\API\Controllers\ConsultationController',
        ['only' => ['index', 'show']]
    );

    Route::resource('location',
        'OParl\Server\API\Controllers\LocationController',
        ['only' => ['index', 'show']]
    );

    Route::resource('file',
        'OParl\Server\API\Controllers\FileController',
        ['only' => ['index', 'show']]
    );
});
