<?php

Route::group(['prefix' => 'api/'], function () {
    Route::get('/', [
        'uses' => 'OParl\Server\API\Controllers\RootController@index',
        'as' => 'api.index'
    ]);
});
