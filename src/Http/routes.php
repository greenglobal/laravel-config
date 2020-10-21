<?php

Route::put('config/updates', 'GGPHP\Config\Http\Controllers\ConfigController@updateConfigs')
    ->name('config-updates');

Route::group(['middleware' => ['web']], function () {
    Route::group(['namespace' => 'GGPHP\Config\Http\Controllers'], function () {
        // Configuration Routes
        Route::prefix('configuration')->group(function () {
            // Throttle Routes
            Route::get('throttles', 'ThrottleController@index')->name('api.throttle.index');
            Route::get('throttle/edit/{id}', 'ThrottleController@edit')->name('api.throttle.edit');
            Route::post('throttle/update', 'ThrottleController@update')->name('api.throttle.update');
        });
    });
});
