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

// Downloads
Route::pattern('downloadVersion', '[a-z0-9]{30}');

Route::get('/downloads', ['uses' => 'DownloadsController@index', 'as' => 'downloads.index']);
Route::get('/downloads/latest', ['uses' => 'DownloadsController@latest', 'as' => 'downloads.latest']);
Route::post('/downloads', ['uses' => 'DownloadsController@selectVersion', 'as' => 'downloads.select']);
Route::get('/downloads/{downloadVersion}', ['uses' => 'DownloadsController@provideVersion', 'as' => 'downloads.provide']);

// Auth
Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
