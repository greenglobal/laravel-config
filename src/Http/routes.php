<?php

Route::group(['middleware' => ['web']], function () {
    Route::group(['namespace' => 'GGPHP\Config\Http\Controllers'], function () {
        // Configuration Routes
        Route::prefix('configuration')->group(function () {
            // Field Routes
            Route::prefix('field')->group(function () {
                Route::patch('updates', 'ConfigController@update')->name('config.field.update');
                Route::get('edit', 'ConfigController@edit')->name('config.field.edit');
                Route::get('reset', 'ConfigController@reset')->name('config.field.reset');
            });

            // Throttle Routes
            Route::get('throttles', 'ThrottleController@index')->name('config.throttle.index');
            Route::get('throttle/edit/{id}', 'ThrottleController@edit')->name('config.throttle.edit');
            Route::post('throttle/update', 'ThrottleController@update')->name('config.throttle.update');
        });
    });
});
