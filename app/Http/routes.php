<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/* @var Illuminate\Routing\Router $router */

/**
 * Route group for dev.oparl.org
 *
 * This route group contains all the endpoints necessary to navigate through
 * dev.oparl.org except the api/ section which is loaded in via the
 * OParl\Server\ServerServiceProvider.
 */
$router->group(['domain' => 'dev.' . config('app.url')], function () use ($router) {
    $router->get('/', ['uses' => 'DevelopersController@index', 'as' => 'developers.index']);

    // Specification
    $router->get('/spezifikation', ['uses' => 'SpecificationController@index', 'as' => 'specification.index']);
    $router->get('/spezifikation.md', ['uses' => 'SpecificationController@raw', 'as' => 'specification.raw']);
    $router->get('/spezifikation/images/',
        ['uses' => 'SpecificationController@imageIndex', 'as' => 'specification.images']);
    $router->get('/spezifikation/images/{image}', 'SpecificationController@image')
        ->name('specification.image')
        ->where('image', '[[:print:]]+\.(png|jpg)');

    $router->get('/spezifikation/builds.json', 'SpecificationController@builds');
    $router->get('/spezifikation/toc.json', 'SpecificationController@toc')->name('specification.toc');

    // Downloads
    $router->get('/downloads/latest.{downloadsExtension}', [
        'uses' => 'DownloadsController@latest',
        'as'   => 'downloads.latest',
    ]);

    $router->get('/spezifikation.{downloadsExtension}', [
        'uses' => 'DownloadsController@latest',
        'as'   => 'specification.download',
    ]);

    $router->get('/downloads/{downloadsVersion}.{downloadsExtension}', [
        'uses' => 'DownloadsController@getFile',
        'as'   => 'downloads.provide',
    ]);

    $router->post('/downloads', ['uses' => 'DownloadsController@selectVersion', 'as' => 'downloads.select']);

    $router->get('/contact', ['uses' => 'DevelopersController@contact', 'as' => 'contact.index']);

    // Hooks
    $router->get('/_hooks', function () {
        return redirect()->route('specification.index');
    });

    $router->get('/_hooks/spec_change', function () {
        return redirect()->route('specification.index');
    });
    $router->post('/_hooks/spec_change', [
        'uses'       => 'HooksController@specChange',
        'as'         => 'hooks.spec',
        'middleware' => ['hooks.github'],
    ]);

    $router->get('/_hooks/add_version', function () {
        return redirect()->route('specification.index');
    });
    $router->post('/_hooks/add_version', [
        'uses' => 'HooksController@addVersion',
        'as'   => 'hooks.add',
    ]);

    $router->get('/_hooks/lock_version_updates', [
        'uses' => 'HooksController@lockVersionUpdates',
        'as'   => 'hooks.lock_vu',
    ]);

    // Dummy file controller for API demo
    $router->pattern('filename', '[a-z0-9]{3,8}');
    $router->get('/demo/{filename}.pdf', ['uses' => 'DummyFileController@show', 'as' => 'dummyfile.show']);
    $router->get('/demo/f/{filename}.pdf', ['uses' => 'DummyFileController@serve', 'as' => 'dummyfile.serve']);
});

/**
 * Route group for spec.oparl.org
 *
 * This route group provides an easy to remember redirect to the
 * latest specification version as spec.oparl.org
 *
 * Additionally, downloads of any specification version are provided
 * via spec.oparl.org/{versionhash}.{format}
 *
 * and for the latest version at spec.oparl.org/latest.{format}
 */
$router->group(['domain' => 'spec.' . config('app.url')], function () use ($router) {
    $router->any('/', function () {
        return redirect()->route('specification.index');
    });

    $router->get('/1.0', function() {
        return redirect()->route('specification.index');
    });

    $router->get('/{downloadsVersion}.{downloadsExtension}', [
        'uses' => 'DownloadsController@getFile',
        'as'   => 'downloads.alternative.provide',
    ]);

    $router->get('/latest.{downloadsExtension}', [
        'uses' => 'DownloadsController@latest',
        'as'   => 'downloads.alternative.latest',
    ]);
});

/**
 * Route group for schema.oparl.org
 *
 * This route group defines access to the versioned JSONSchema of the OParl Specification
 * which is accessible at schema.oparl.org/{version}/{entity}.json
 *
 * Direct access to schema.oparl.org is redirected to dev.oparl.org
 */
$router->group(['domain' => 'schema.' . config('app.url')], function () use ($router) {
    $router->get('/', function () {
        return redirect()->route('developers.index');
    });

    $router->get('/1.0/{entity}', [
        'uses' => 'SchemaController@getSchema',
        'as'   => 'schema.get',
    ])->where('entity', '[A-Za-z]+');
});
