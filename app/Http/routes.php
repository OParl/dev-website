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
Route::pattern('year', '\d{4}');
Route::pattern('month', '\d{2}');
Route::pattern('day', '\d{2}');
Route::pattern('slug', '[[:print:]]+');

Route::get('/', ['uses' => function () { return redirect('/spezifikation'); }, 'as' => 'news.index']);

// FIXME: reactivate when news are being used
//Route::get('/', ['uses' => 'NewsController@index', 'as' => 'news.index']);

// About
Route::get('/ueber-oparl', ['uses' => 'StaticPagesController@about', 'as' => 'about.index']);

// Specification
Route::get('/spezifikation', ['uses' => 'SpecificationController@index', 'as' => 'specification.index']);
Route::get('/spezifikation.md', ['uses' => 'SpecificationController@raw', 'as' => 'specification.raw']);
Route::get('/spezifikation/images/{image}', [
  'uses' => 'SpecificationController@image',
  'as' => 'specification.image'
])->where('image', '[[:print:]]+\.png');

// Downloads
Route::pattern('downloadsExtension', '(docx|txt|pdf|odt|html|epub|zip|tar\.gz|tar\.bz2)');
Route::pattern('downloadsVersion', '[a-z0-9]{7}');

Route::get('/downloads', ['uses' => 'DownloadsController@index', 'as' => 'downloads.index']);
Route::get('/downloads/latest.{downloadsExtension}', [
  'uses' => 'DownloadsController@latest',
  'as' => 'downloads.latest'

]);
Route::get('/downloads/{downloadsVersion}.{downloadsExtension}', [
  'uses' => 'DownloadsController@getFile',
  'as' => 'downloads.provide'
]);

Route::post('/downloads', ['uses' => 'DownloadsController@selectVersion', 'as' => 'downloads.select']);

// Status
Route::get('/status', ['uses' => 'StaticPagesController@status', 'as' => 'status.index']);

// Imprint
Route::get('/impressum', ['uses' => 'StaticPagesController@imprint', 'as' => 'imprint.index']);

// Admin Login
Route::get('/admin/login', ['uses' => 'Auth\AuthController@getLogin', 'as' => 'admin.login']);
Route::post('/admin/login', ['uses' => 'Auth\AuthController@postLogin', 'as' => 'admin.perform_login']);

Route::get('/admin/logout', ['uses' => 'Auth\AuthController@getLogout', 'as' => 'admin.logout']);

// Admin
Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function () {
  Route::pattern('id', '\d+');

  Route::get('/', ['uses' => 'Admin\DashboardController@index', 'as' => 'admin.dashboard.index']);

  Route::get('/posts', ['uses' => 'Admin\NewsController@index', 'as' => 'admin.news.index']);
  Route::get('/posts/new', ['uses' => 'Admin\NewsController@create', 'as' => 'admin.news.create']);
  Route::get('/posts/{id}', ['uses' => 'Admin\NewsController@edit', 'as' => 'admin.news.edit']);
  Route::post('/posts', ['uses' => 'Admin\NewsController@save', 'as' => 'admin.news.save']);
  Route::get('/posts/{id}/delete', ['uses' => 'Admin\NewsController@delete', 'as' => 'admin.news.delete']);
  Route::post('/posts/slug/', ['uses' => 'Admin\NewsController@slug', 'as' => 'admin.news.slug']);

  Route::get('/comments', ['uses' => 'Admin\CommentsController@index', 'as' => 'admin.comments.index']);
  Route::post('/comments', ['uses' => 'Admin\CommentsController@save', 'as' => 'admin.comments.save']);
  Route::post('/comments/status', ['uses' => 'Admin\CommentsController@index', 'as' => 'admin.comments.status']);
  Route::get('/comments/{id}', ['uses' => 'Admin\CommentsController@edit', 'as' => 'admin.comments.edit']);
  Route::get('/comments/{id}/delete', ['uses' => 'Admin\CommentsController@delete', 'as' => 'admin.comments.delete']);

  Route::get('/spec', ['uses' => 'Admin\SpecificationController@index', 'as' => 'admin.specification.index']);
  Route::get('/spec/update/{what}', ['uses' => 'Admin\SpecificationController@index', 'as' => 'admin.specification.update'])
    ->where('what', '(livecopy|livecopy-force|versions)');
  Route::get('/spec/clean/{what}', ['uses' => 'Admin\SpecificationController@clean', 'as' => 'admin.specification.clean'])
    ->where('what', '(all|extraneous)');
  Route::get('/spec/fetch/{what}', ['uses' => 'Admin\SpecificationController@fetch', 'as' => 'admin.specification.fetch'])
    ->where('what', '(_missing_|[a-z0-9]{5,})');
  Route::get('/spec/delete/{hash}', ['uses' => 'Admin\SpecificationController@delete', 'as' => 'admin.specification.delete'])
    ->where('hash', '[a-z0-9]{5,}');

  Route::get('/settings', ['uses' => 'Admin\SettingsController@index', 'as' => 'admin.settings']);
  Route::post('/settings', ['uses' => 'Admin\SettingsController@save', 'as' => 'admin.settings.save']);
});

// Hooks
Route::get('/_hooks', function () { return redirect()->route('specification.index'); });

Route::get('/_hooks/spec_change', function () { return redirect()->route('specification.index'); });
Route::post('/_hooks/spec_change', [
  'uses' => 'HooksController@specChange',
  'as' => 'hooks.spec',
  'middleware' => ['hooks.github']
]);

Route::get('/_hooks/add_version', function() { return redirect()->route('specification.index'); });
Route::post('/_hooks/add_version', [
  'uses' => 'HooksController@addVersion',
  'as' => 'hooks.add'
]);

Route::get('/_hooks/lock_version_updates', [
  'uses' => 'HooksController@lockVersionUpdates',
  'as' => 'hooks.lock_vu'
]);

// News and Search

Route::post('/search', ['uses' => 'SearchController@search', 'as' => 'search.lookup']);

Route::get('/{year}', ['uses' => 'NewsController@yearly', 'as' => 'news.yearly']);
Route::get('/{year}/{month}', ['uses' => 'NewsController@monthly', 'as' => 'news.monthly']);
Route::get('/{year}/{month}/{day}', ['uses' => 'NewsController@daily', 'as' => 'news.daily']);
Route::get('/{year}/{month}/{day}/{slug}', ['uses' => 'NewsController@post', 'as' => 'news.post']);
Route::post('/{year}/{month}/{day}/{slug}', ['uses' => 'NewsController@comment', 'as' => 'news.comment']);
Route::get('/tags/{tag}', ['uses' => 'NewsController@tag', 'as' => 'news.tag'])->where('tag', '[[:print:]]+');

// as a last resort, try guessing the input as post slug
Route::get('/{slug}', ['uses' => 'NewsController@guess', 'as' => 'news.guess']);
