<?php

namespace GGPHP\Config\Providers;

use Illuminate\Support\ServiceProvider;

class ConfigServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/config.php' => config_path('ggphpconfig.php'),
        ], 'config');

        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'ggphp-config');
        $this->loadRoutesFrom(__DIR__.'/../Http/routes.php');
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom( __DIR__.'/../../config/test.php', 'laravelconfig.fields');
    }
}
