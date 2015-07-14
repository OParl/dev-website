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
Route::get('/spezifikation', ['uses' => 'SpecificationController@index', 'as' => 'specification.index']);
Route::get('/spezifikation.md', ['uses' => 'SpecificationController@raw', 'as' => 'specification.raw']);
Route::get('/spezifikation/images/{image}', [
  'uses' => 'SpecificationController@image',
  'as' => 'specification.image'
])->where('image', '[[:print:]]+\.png');

// Downloads
Route::get('/downloads', ['uses' => 'DownloadsController@index', 'as' => 'downloads.index']);
Route::get('/downloads/latest', ['uses' => 'DownloadsController@latest', 'as' => 'downloads.latest']);
Route::get('/downloads/{version}.{extension}', [
  'uses' => 'DownloadsController@getFile',
  'as' => 'downloads.provide'
])->where('version', '[a-z0-9]{7}')->where('extension', '(docx|txt|pdf|odt|html|epub|zip|tar.gz|tar.bz2)');
Route::post('/downloads', ['uses' => 'DownloadsController@selectVersion', 'as' => 'downloads.select']);

// Auth
Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

// Hooks
Route::get('/_hooks', function () { return redirect()->route('specification.index'); });

Route::get('/_hooks/spec_change', function () { return redirect()->route('specification.index'); });
Route::post('/_hooks/spec_change', [
  'uses' => 'HooksController@specChange',
  'as' => 'hooks.spec',
  'middleware' => ['hooks.github']
]);

Route::get('/_hooks/add_version', function() { return redirect()->route('specification.index'); });
Route::post('/_hooks/add_version/{key}/{version}', [
  'uses' => 'HooksController@addVersion',
  'as' => 'hooks.add'
])->where('key', '[a-zA-Z0-9]{32}')
  ->where('version', '[a-z0-9]{4,10}');

