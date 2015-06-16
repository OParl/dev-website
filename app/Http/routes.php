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

Route::get('/', function () {
  return Redirect::route('specification');
});

Route::get('/specification', ['uses' => 'SpecificationController@index', 'as' => 'specification.index']);
Route::get('/downloads', ['uses' => 'DownloadsController@index', 'as' => 'downloads.index']);

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
