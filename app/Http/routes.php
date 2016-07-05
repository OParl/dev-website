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

Route::get('/', ['uses' => 'DevelopersController@index', 'as' => 'developers.index']);

// Specification
Route::get('/spezifikation', ['uses' => 'SpecificationController@index', 'as' => 'specification.index']);
Route::get('/spezifikation.md', ['uses' => 'SpecificationController@raw', 'as' => 'specification.raw']);
Route::get('/spezifikation/images/', ['uses' => 'SpecificationController@imageIndex', 'as' => 'specification.images']);
Route::get('/spezifikation/images/{image}', [
    'uses' => 'SpecificationController@image',
    'as'   => 'specification.image',
])->where('image', '[[:print:]]+\.(png|jpg)');

Route::get('/spezifikation/builds.json', 'SpecificationController@builds');

// Downloads
Route::pattern('downloadsExtension', '(docx|txt|pdf|odt|html|epub|zip|tar\.gz|tar\.bz2)');
Route::pattern('downloadsVersion', '[a-z0-9]{7}');

//Route::get('/downloads', ['uses' => 'DownloadsController@index', 'as' => 'downloads.index']);
Route::get('/downloads/latest.{downloadsExtension}', [
    'uses' => 'DownloadsController@latest',
    'as'   => 'downloads.latest',
]);
Route::get('/spezifikation.{downloadsExtension}', [
    'uses' => 'DownloadsController@latest',
    'as'   => 'specification.download',
]);
Route::get('/downloads/{downloadsVersion}.{downloadsExtension}', [
    'uses' => 'DownloadsController@getFile',
    'as'   => 'downloads.provide',
]);

Route::post('/downloads', ['uses' => 'DownloadsController@selectVersion', 'as' => 'downloads.select']);

// Hooks
Route::get('/_hooks', function () {
    return redirect()->route('specification.index');
});

Route::get('/_hooks/spec_change', function () {
    return redirect()->route('specification.index');
});
Route::post('/_hooks/spec_change', [
    'uses'       => 'HooksController@specChange',
    'as'         => 'hooks.spec',
    'middleware' => ['hooks.github'],
]);

Route::get('/_hooks/add_version', function () {
    return redirect()->route('specification.index');
});
Route::post('/_hooks/add_version', [
    'uses' => 'HooksController@addVersion',
    'as'   => 'hooks.add',
]);

Route::get('/_hooks/lock_version_updates', [
    'uses' => 'HooksController@lockVersionUpdates',
    'as'   => 'hooks.lock_vu',
]);

Route::pattern('filename', '[a-z0-9]{3,8}');
Route::get('/demo/{filename}.pdf', ['uses' => 'DummyFileController@show', 'as' => 'dummyfile.show']);
Route::get('/demo/f/{filename}.pdf', ['uses' => 'DummyFileController@serve', 'as' => 'dummyfile.serve']);
