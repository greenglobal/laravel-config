<?php

Route::put('config/updates', 'GGPHP\Config\Http\Controllers\ConfigController@updateConfigs')
    ->name('config-updates');
