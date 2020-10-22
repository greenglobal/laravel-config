<?php

Route::put('config/updates', 'GGPHP\Config\Http\Controllers\ConfigController@updateConfigs')
    ->name('config-updates');
Route::get('config/reset', 'GGPHP\Config\Http\Controllers\ConfigController@reset')
    ->name('config-reset');
