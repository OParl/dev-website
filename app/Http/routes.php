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

// Home
Route::get('/', function () {
  return Redirect::route('specification.index');
});

// Specification
Route::get('/specification', ['uses' => 'SpecificationController@index', 'as' => 'specification.index']);
Route::get('/specification/images/{image}', [
  'uses' => 'SpecificationController@image',
  'as' => 'specification.image'
])->where('image', '[[:print:]]+\.png');

// Downloads
Route::get('/downloads', ['uses' => 'DownloadsController@index', 'as' => 'downloads.index']);
Route::get('/downloads/latest', ['uses' => 'DownloadsController@latest', 'as' => 'downloads.latest']);
Route::post('/downloads', ['uses' => 'DownloadsController@selectVersion', 'as' => 'downloads.select']);
Route::get('/downloads/{version}', [
  'uses' => 'DownloadsController@provideVersion',
  'as' => 'downloads.provide'
])->where('version', '[a-z0-9]{30}');

// Auth
Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

// Hooks
Route::post('/_hooks/spec_change', [
  'uses' => 'HooksController@specChange',
  'as' => 'hooks.spec',
  'middleware' => ['hooks.github']
]);
Route::get('/_hooks/add_version', ['uses' => 'HooksController@addVersion', 'as' => 'hooks.add']);
