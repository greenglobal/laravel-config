<?php

Route::group(['middleware' => ['web']], function () {
    Route::group(['namespace' => 'GGPHP\Config\Http\Controllers'], function () {
        // Configuration Routes
        Route::prefix('configuration')->group(function () {
            Route::put('updates', 'ConfigController@updateConfigs')->name('config-updates');
            Route::get('reset', 'GGPHP\Config\Http\Controllers\ConfigController@reset')->name('config-reset');
            
            // Throttle Routes
            Route::get('throttles', 'ThrottleController@index')->name('api.throttle.index');
            Route::get('throttle/edit/{id}', 'ThrottleController@edit')->name('api.throttle.edit');
            Route::post('throttle/update', 'ThrottleController@update')->name('api.throttle.update');
        });
    });
});
