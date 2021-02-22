<?php

Route::group(['middleware' => ['web']], function () {
    Route::group(['namespace' => 'GGPHP\Config\Http\Controllers'], function () {
        // Configuration Routes
        Route::prefix('configuration')->group(function () {
            // Field Routes
            Route::prefix('field')->group(function () {
                Route::patch('updates', 'ConfigController@update')->name('config.field.update');
                Route::get('edit', 'ConfigController@edit')->name('config.field.edit');
            });

            // Throttle Routes
            Route::get('throttles', 'ThrottleController@index')->name('config.throttle.index');
            Route::get('throttle/edit/{name}', 'ThrottleController@edit')->name('config.throttle.edit');
            Route::post('throttle/update', 'ThrottleController@update')->name('config.throttle.update');
            Route::get('throttle/reset', 'ThrottleController@reset')->name('config.throttle.reset');
        });
    });
});

Route::group(['prefix' => 'api', 'middleware' => ['throttle']], function () {
    Route::group(['namespace' => 'GGPHP\Config\Http\Controllers\API'], function () {
        // API configuration fields
        Route::get('configuration/fields/reset', 'ConfigController@reset')->name('api.field.reset');
        Route::get('configuration/fields', 'ConfigController@index')->name('api.field.index');
        Route::get('configuration/fields/{id}', 'ConfigController@get')->name('api.field.get');
        Route::post('configuration/fields', 'ConfigController@create')->name('api.field.create');
        Route::patch('configuration/fields', 'ConfigController@update')->name('api.field.update');
        Route::delete('configuration/fields/{id}', 'ConfigController@delete')->name('api.field.delete');

        // API configuration throttles
        Route::get('configuration/throttles', 'ThrottleController@index')->name('api.throttles.index');
        Route::get('configuration/throttles/reset', 'ThrottleController@reset')->name('api.throttles.reset');
        Route::get('configuration/throttles/{name}', 'ThrottleController@get')->name('api.throttles.get');
        Route::post('configuration/throttles', 'ThrottleController@update')->name('api.throttles.update');
    });
});

// Routes for testing
if (app('env') == 'testing') {
    Route::group(['prefix' => 'api', 'middleware' => ['throttle']], function () {
        Route::get('throttle', function () {
            return ['message' => 'Call api successfully!'];
        })->name('api.throttle.get');

        Route::post('throttle', function () {
            return ['message' => 'Call api successfully!'];
        })->name('api.throttle.post');

        Route::put('throttle', function () {
            return ['message' => 'Call api successfully!'];
        })->name('api.throttle.put');

        Route::patch('throttle/{id}', function () {
            return ['message' => 'Call api successfully!'];
        })->name('api.throttle.patch');

        Route::delete('throttle/{id}', function () {
            return ['message' => 'Call api successfully!'];
        })->name('api.throttle.delete');
    });
}
